#pragma once

// ---- Wi-Fi (samo za preuzimanje tačnog vremena sa interneta) ----
// Upiši svoju mrežu ovde.
#define WIFI_IME      "ime_mreze"
#define WIFI_LOZINKA  "lozinka"

// Vremenska zona Srbije (automatski letnje/zimsko računanje vremena)
#define VREMENSKA_ZONA "CET-1CEST,M3.5.0,M10.5.0/3"

// ---- MAX98357A (zvučnik) ----
#define PIN_ZVUK_BCLK  4
#define PIN_ZVUK_LRC   5
#define PIN_ZVUK_DIN   6

// ---- INMP441 (mikrofon) ----
#define PIN_MIC_SCK    15
#define PIN_MIC_WS     16
#define PIN_MIC_SD     17

// ---- Taster ----
// 0 = ugrađeni BOOT taster na ploči. Spoljni taster: između pina i GND.
#define PIN_TASTER     0

// Jačina zvuka 0.0 - 1.0
#define JACINA         0.6f
