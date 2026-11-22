# Govorni sat za slabovide — kontekst projekta

Komunikacija sa korisnikom: **srpski (latinica)**.

## Cilj
Uređaj za slabovide: korisnik kaže frazu (npr. „Hej Sat“) ili pritisne taster, a uređaj
izgovori tačno vreme na srpskom: *„Sada je četrnaest sati i dvadeset tri minuta.“*
Sve radi offline na samom uređaju (osim NTP sinhronizacije vremena preko Wi-Fi-ja).

## Hardver koji korisnik ima
- **ESP32-WROOM-32** (DevKit V1, 30 pinova) — izabran za ovaj projekat.
  ESP32-S3 korisnik čuva za druge projekte; prelazimo na S3 samo ako wake word ne radi dobro na WROOM-u.
- **MAX98357A** I2S pojačalo + zvučnik (povezano)
- **INMP441** I2S mikrofon (pinovi L/R, WS, SCK, GND, VDD, SD) — još nije povezan
- Nema RTC modula (DS3231 je predložen za rad bez Wi-Fi-ja)

## Povezivanje (WROOM-32)
| Modul | Pin | ESP32 |
|---|---|---|
| MAX98357A | VIN / GND | VIN (5V) / GND |
| | BCLK / LRC / DIN | GPIO 26 / 25 / 22 |
| | GAIN, SD | nepovezani |
| INMP441 | VDD / GND | **3V3** / GND |
| | L/R | GND |
| | SCK / WS / SD | GPIO 32 / 33 / 35 |
| Taster | | ugrađeni BOOT (GPIO 0) |

## Arhitektura
- `include/srpsko_vreme.h` — gramatika: sat/minut → lista zvučnih isečaka
  (jednina/paukal/množina: sat/sata/sati, minut/minuta). Sve muški rod („jedan minut“, „dva minuta“).
  Testirano svih 1440 vremena: `test/test_gramatika.cpp`.
- `data/*.wav` — 33 isečka, 16 kHz mono 16-bit, idu u LittleFS (`pio run -t uploadfs`).
  Trenutno robotski espeak-ng glas; lep glas: `tools/generisi_glas.py` (edge-tts, sr-RS-NicholasNeural / SophieNeural).
- `src/main.cpp` — Arduino/PlatformIO, legacy `driver/i2s.h`; WAV se pušta kao stereo (duplirani mono uzorak).
  Vreme preko NTP, zona `CET-1CEST,M3.5.0,M10.5.0/3`. Serijski: `v` = izgovori vreme, `m` = test mikrofona.
- `include/podesavanja.h` — Wi-Fi, pinovi, jačina.
- `test_zvucnika/` — samostalan test zvučnika (tišina 3 s → ton 440 Hz → tri pištanja).

## Status
- Firmware je napisan, ali **još nikad nije preveden ni testiran na ploči**
  (prethodna sesija je bila u oblaku bez pristupa PlatformIO registru i USB-u).
- Korisnik je povezao samo zvučnik; zvučnik **stalno krči** dok ESP32 ne šalje zvuk
  (verovatno plutajući I2S ulazi — očekivano bez programa).

## Sledeći koraci
1. Prevesti i prebaciti `test_zvucnika/` na ploču, proveriti da li krčanje prestaje u tišini i da li je ton čist.
2. Upisati Wi-Fi u `podesavanja.h`, `uploadfs` + `upload` glavnog programa, proveriti izgovor vremena.
3. Generisati lep glas (edge-tts) na korisnikovom računaru.
4. Povezati INMP441, proveriti komandom `m`.
5. Faza 2: wake word „Hej Sat“ preko microWakeWord (TFLite Micro) na WROOM-32.
6. Kasnije: DS3231, baterija, kućište, datum na dugi pritisak, alarm/podsetnik.
