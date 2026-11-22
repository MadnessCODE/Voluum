#pragma once

// ---- Wi-Fi (samo za preuzimanje tačnog vremena sa interneta) ----
// Upiši svoju mrežu ovde.
#define WIFI_IME      "ime_mreze"
#define WIFI_LOZINKA  "lozinka"

// Vremenska zona Srbije (automatski letnje/zimsko računanje vremena)
#define VREMENSKA_ZONA "CET-1CEST,M3.5.0,M10.5.0/3"

// ---- MAX98357A (zvučnik) ----
#define PIN_ZVUK_BCLK  26
#define PIN_ZVUK_LRC   25
#define PIN_ZVUK_DIN   22

// ---- INMP441 (mikrofon) ----  L/R -> GND, VDD -> 3.3V (NE 5V!)
#define PIN_MIC_SCK    32
#define PIN_MIC_WS     33
#define PIN_MIC_SD     35

// ---- Taster ----
// 0 = ugrađeni BOOT taster na ploči. Spoljni taster: između pina i GND.
#define PIN_TASTER     0

// Jačina zvuka 0.0 - 1.0
#define JACINA         0.6f
