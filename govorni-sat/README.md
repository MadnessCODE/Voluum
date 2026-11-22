# Govorni sat za slabovide

Pritisneš taster i uređaj izgovori tačno vreme na srpskom:
*„Sada je četrnaest sati i dvadeset tri minuta.“*
Sledeća faza je da reaguje na glasovnu frazu „Hej Sat“.

## Delovi
- ESP32-WROOM-32 ploča (DevKit)
- MAX98357A + zvučnik
- INMP441 mikrofon (za sada samo test, koristi se u fazi 2)

## Povezivanje

| Modul | Pin | ESP32 |
|---|---|---|
| MAX98357A | VIN | 5V (VIN) |
| | GND | GND |
| | BCLK | GPIO 26 |
| | LRC | GPIO 25 |
| | DIN | GPIO 22 |
| INMP441 | VDD | **3.3V** (ne 5V!) |
| | GND | GND |
| | L/R | GND |
| | SCK | GPIO 32 |
| | WS | GPIO 33 |
| | SD | GPIO 35 |
| Taster | | ugrađeni BOOT (GPIO 0) |

Pinovi se menjaju u `include/podesavanja.h`.

## Pokretanje
1. Instaliraj **VS Code** i dodatak **PlatformIO IDE**, pa otvori ovaj folder.
2. U `include/podesavanja.h` upiši ime i lozinku svoje Wi-Fi mreže.
3. Prebaci glasovne fajlove na ploču: PlatformIO → *Platform* → **Upload Filesystem Image**
   (komandna linija: `pio run -t uploadfs`).
4. Prebaci program: **Upload** (`pio run -t upload`).
5. Otvori **Serial Monitor** (115200). Posle povezivanja na Wi-Fi uređaj sam izgovori vreme.

Komande u Serial Monitoru:
- `v` izgovori vreme
- `m` pokreće test mikrofona (5 s ispisuje jačinu zvuka; kad pričaš, brojevi treba da rastu)

## Lepši glas
Fajlovi u `data/` su trenutno robotski (espeak). Prirodan srpski glas napraviš na svom računaru
(treba Python i ffmpeg):

```
pip install edge-tts
python tools/generisi_glas.py                              # muški glas
python tools/generisi_glas.py --glas sr-RS-SophieNeural    # ženski glas
```

Posle toga ponovo uradi **Upload Filesystem Image**.

## Test gramatike (na računaru)
```
g++ -std=c++17 -Iinclude test/test_gramatika.cpp -o test_g && ./test_g
```
