// TEST ZVUČNIKA - MAX98357A na ESP32-WROOM-32
// Ponavlja u krug: 3 s tišine -> ton 1 s -> tri pištanja.
// Arduino IDE: kopiraj ovaj fajl u novi sketch (ploča "ESP32 Dev Module").
#include <Arduino.h>
#include <driver/i2s.h>
#include <math.h>

#define PIN_BCLK 26
#define PIN_LRC  25
#define PIN_DIN  22

static const int FS = 16000;      // frekvencija uzorkovanja
static const float JACINA = 0.3f; // 0.0 - 1.0 (krenimo tiše)

static void pokreniI2S() {
  i2s_config_t cfg = {};
  cfg.mode = (i2s_mode_t)(I2S_MODE_MASTER | I2S_MODE_TX);
  cfg.sample_rate = FS;
  cfg.bits_per_sample = I2S_BITS_PER_SAMPLE_16BIT;
  cfg.channel_format = I2S_CHANNEL_FMT_RIGHT_LEFT;
  cfg.communication_format = I2S_COMM_FORMAT_STAND_I2S;
  cfg.dma_buf_count = 8;
  cfg.dma_buf_len = 256;
  cfg.tx_desc_auto_clear = true;
  i2s_pin_config_t pini = {};
  pini.mck_io_num = I2S_PIN_NO_CHANGE;
  pini.bck_io_num = PIN_BCLK;
  pini.ws_io_num = PIN_LRC;
  pini.data_out_num = PIN_DIN;
  pini.data_in_num = I2S_PIN_NO_CHANGE;
  esp_err_t e1 = i2s_driver_install(I2S_NUM_0, &cfg, 0, nullptr);
  esp_err_t e2 = i2s_set_pin(I2S_NUM_0, &pini);
  Serial.printf("I2S: %s\n", (e1 == ESP_OK && e2 == ESP_OK) ? "OK" : "GREŠKA");
  i2s_zero_dma_buffer(I2S_NUM_0);
}

// Sinusni ton sa blagim početkom i krajem (da ne pucketa).
static void ton(float hz, int ms, float jacina) {
  static int16_t buf[512];  // 256 stereo uzoraka
  const int ukupno = FS * ms / 1000;
  const int rampa = FS / 200;  // 5 ms
  float faza = 0;
  int i = 0;
  while (i < ukupno) {
    int n = min(256, ukupno - i);
    for (int k = 0; k < n; k++, i++) {
      float omotac = min(1.0f, min((float)i / rampa, (float)(ukupno - i) / rampa));
      int16_t s = (int16_t)(jacina * omotac * 32767.0f * sinf(faza));
      faza += 2.0f * PI * hz / FS;
      if (faza > 2.0f * PI) faza -= 2.0f * PI;
      buf[2 * k] = s;
      buf[2 * k + 1] = s;
    }
    size_t upisano;
    i2s_write(I2S_NUM_0, buf, n * 4, &upisano, portMAX_DELAY);
  }
}

static void tisina(int ms) { ton(0, ms, 0); }

void setup() {
  Serial.begin(115200);
  delay(500);
  Serial.println("\n=== TEST ZVUČNIKA ===");
  pokreniI2S();
}

void loop() {
  Serial.println("1) TIŠINA 3 s  -> zvučnik treba da bude tih (možda blago šušti)");
  tisina(3000);
  Serial.println("2) TON 440 Hz 1 s");
  ton(440, 1000, JACINA);
  tisina(300);
  Serial.println("3) Tri pištanja (do - mi - sol)");
  ton(523, 250, JACINA); tisina(100);
  ton(659, 250, JACINA); tisina(100);
  ton(784, 400, JACINA);
}
