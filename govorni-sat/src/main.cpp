// Govorni sat za slabovide - Faza 1
// Pritisak na taster -> uređaj izgovori tačno vreme na srpskom.
// Serijski monitor (115200): "v" = izgovori vreme, "m" = test mikrofona.
#include <Arduino.h>
#include <LittleFS.h>
#include <WiFi.h>
#include <driver/i2s.h>
#include <time.h>

#include "podesavanja.h"
#include "srpsko_vreme.h"

static const i2s_port_t ZVUK = I2S_NUM_0;
static const i2s_port_t MIC = I2S_NUM_1;
static const uint32_t FREKVENCIJA = 16000;  // svi WAV fajlovi su 16 kHz mono

// ------------------------------------------------------------ zvuk
static void pokreniZvuk() {
  i2s_config_t cfg = {};
  cfg.mode = (i2s_mode_t)(I2S_MODE_MASTER | I2S_MODE_TX);
  cfg.sample_rate = FREKVENCIJA;
  cfg.bits_per_sample = I2S_BITS_PER_SAMPLE_16BIT;
  cfg.channel_format = I2S_CHANNEL_FMT_RIGHT_LEFT;
  cfg.communication_format = I2S_COMM_FORMAT_STAND_I2S;
  cfg.dma_buf_count = 8;
  cfg.dma_buf_len = 256;
  cfg.tx_desc_auto_clear = true;  // tišina kad nema podataka (bez zujanja)
  i2s_pin_config_t pini = {};
  pini.mck_io_num = I2S_PIN_NO_CHANGE;
  pini.bck_io_num = PIN_ZVUK_BCLK;
  pini.ws_io_num = PIN_ZVUK_LRC;
  pini.data_out_num = PIN_ZVUK_DIN;
  pini.data_in_num = I2S_PIN_NO_CHANGE;
  i2s_driver_install(ZVUK, &cfg, 0, nullptr);
  i2s_set_pin(ZVUK, &pini);
  i2s_zero_dma_buffer(ZVUK);
}

// Pušta jedan WAV iz LittleFS-a. Vraća false ako fajl ne postoji.
static bool pusti(const std::string& ime) {
  String put = String("/") + ime.c_str() + ".wav";
  File f = LittleFS.open(put, "r");
  if (!f) {
    Serial.printf("Nema fajla %s\n", put.c_str());
    return false;
  }
  // Pronađi "data" blok u WAV fajlu
  f.seek(12);
  uint8_t zaglavlje[8];
  uint32_t velicina = 0;
  while (f.read(zaglavlje, 8) == 8) {
    velicina = zaglavlje[4] | zaglavlje[5] << 8 | zaglavlje[6] << 16 | zaglavlje[7] << 24;
    if (memcmp(zaglavlje, "data", 4) == 0) break;
    f.seek(f.position() + velicina + (velicina & 1));
    velicina = 0;
  }

  static int16_t mono[256];
  static int16_t stereo[512];
  while (velicina > 0) {
    size_t n = f.read((uint8_t*)mono, min<uint32_t>(sizeof(mono), velicina));
    if (n == 0) break;
    velicina -= n;
    size_t uzoraka = n / 2;
    for (size_t i = 0; i < uzoraka; i++) {
      int16_t s = (int16_t)(mono[i] * JACINA);
      stereo[2 * i] = s;
      stereo[2 * i + 1] = s;
    }
    size_t upisano;
    i2s_write(ZVUK, stereo, uzoraka * 4, &upisano, portMAX_DELAY);
  }
  f.close();
  return true;
}

static void izgovoriVreme() {
  struct tm t;
  if (!getLocalTime(&t, 100) || t.tm_year < 120) {
    Serial.println("Vreme nije podešeno");
    pusti("nema_vremena");
    return;
  }
  Serial.printf("%02d:%02d  ->", t.tm_hour, t.tm_min);
  for (auto& isecak : sv::recenica(t.tm_hour, t.tm_min)) {
    Serial.printf(" %s", isecak.c_str());
    pusti(isecak);
  }
  Serial.println();
  // malo tišine da se bafer isprazni
  static int16_t nule[512] = {};
  size_t upisano;
  for (int i = 0; i < 4; i++) i2s_write(ZVUK, nule, sizeof(nule), &upisano, portMAX_DELAY);
}

// ------------------------------------------------------------ mikrofon (test)
static void pokreniMikrofon() {
  i2s_config_t cfg = {};
  cfg.mode = (i2s_mode_t)(I2S_MODE_MASTER | I2S_MODE_RX);
  cfg.sample_rate = FREKVENCIJA;
  cfg.bits_per_sample = I2S_BITS_PER_SAMPLE_32BIT;
  cfg.channel_format = I2S_CHANNEL_FMT_ONLY_LEFT;  // L/R pin mikrofona na GND
  cfg.communication_format = I2S_COMM_FORMAT_STAND_I2S;
  cfg.dma_buf_count = 4;
  cfg.dma_buf_len = 256;
  i2s_pin_config_t pini = {};
  pini.mck_io_num = I2S_PIN_NO_CHANGE;
  pini.bck_io_num = PIN_MIC_SCK;
  pini.ws_io_num = PIN_MIC_WS;
  pini.data_out_num = I2S_PIN_NO_CHANGE;
  pini.data_in_num = PIN_MIC_SD;
  i2s_driver_install(MIC, &cfg, 0, nullptr);
  i2s_set_pin(MIC, &pini);
}

// 5 sekundi ispisuje jačinu zvuka sa mikrofona. Pričaj ili pucni prstima.
static void testMikrofona() {
  Serial.println("Test mikrofona 5 s - pričaj u mikrofon...");
  static int32_t buf[256];
  uint32_t kraj = millis() + 5000;
  while (millis() < kraj) {
    size_t procitano;
    i2s_read(MIC, buf, sizeof(buf), &procitano, portMAX_DELAY);
    int n = procitano / 4;
    double zbir = 0, srednja = 0;
    for (int i = 0; i < n; i++) srednja += buf[i] >> 14;
    srednja /= n;
    for (int i = 0; i < n; i++) {
      double s = (buf[i] >> 14) - srednja;
      zbir += s * s;
    }
    int rms = (int)sqrt(zbir / n);
    int crtice = min(60, rms / 20);
    Serial.printf("%6d |%.*s\n", rms, crtice,
                  "############################################################");
  }
  Serial.println("Ako su brojevi stalno 0 ili se ne menjaju kad pričaš, proveri žice.");
}

// ------------------------------------------------------------ vreme
static void preuzmiVreme() {
  Serial.printf("Povezujem se na Wi-Fi \"%s\"...\n", WIFI_IME);
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_IME, WIFI_LOZINKA);
  uint32_t pocetak = millis();
  while (WiFi.status() != WL_CONNECTED && millis() - pocetak < 15000) delay(250);
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("Wi-Fi nije uspeo.");
    return;
  }
  // Wi-Fi ostaje uključen, pa se sat sam usklađuje svakog sata.
  configTzTime(VREMENSKA_ZONA, "pool.ntp.org", "time.google.com");
  struct tm t;
  if (getLocalTime(&t, 10000))
    Serial.printf("Vreme preuzeto: %02d:%02d:%02d\n", t.tm_hour, t.tm_min, t.tm_sec);
  else
    Serial.println("NTP nije odgovorio.");
}

// ------------------------------------------------------------ glavno
void setup() {
  Serial.begin(115200);
  delay(500);
  Serial.println("\n=== Govorni sat ===");
  pinMode(PIN_TASTER, INPUT_PULLUP);
  if (!LittleFS.begin()) Serial.println("LittleFS greška - da li si uradio 'Upload Filesystem Image'?");
  pokreniZvuk();
  pokreniMikrofon();
  preuzmiVreme();
  izgovoriVreme();  // potvrda da sve radi
}

void loop() {
  static bool bioPritisnut = false;
  bool pritisnut = digitalRead(PIN_TASTER) == LOW;
  if (pritisnut && !bioPritisnut) {
    delay(30);  // debounce
    if (digitalRead(PIN_TASTER) == LOW) izgovoriVreme();
  }
  bioPritisnut = pritisnut;

  if (Serial.available()) {
    char c = Serial.read();
    if (c == 'v') izgovoriVreme();
    if (c == 'm') testMikrofona();
  }
  delay(10);
}
