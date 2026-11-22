// Test gramatike na računaru (ne treba ESP32):
//   g++ -std=c++17 -Iinclude test/test_gramatika.cpp -o /tmp/t && /tmp/t
#include <cstdio>
#include <map>
#include "srpsko_vreme.h"

static const std::map<std::string, std::string> TEKST = {
    {"0", "nula"}, {"1", "jedan"}, {"2", "dva"}, {"3", "tri"}, {"4", "četiri"},
    {"5", "pet"}, {"6", "šest"}, {"7", "sedam"}, {"8", "osam"}, {"9", "devet"},
    {"10", "deset"}, {"11", "jedanaest"}, {"12", "dvanaest"}, {"13", "trinaest"},
    {"14", "četrnaest"}, {"15", "petnaest"}, {"16", "šesnaest"},
    {"17", "sedamnaest"}, {"18", "osamnaest"}, {"19", "devetnaest"},
    {"20", "dvadeset"}, {"30", "trideset"}, {"40", "četrdeset"},
    {"50", "pedeset"}, {"sada_je", "Sada je"}, {"tacno", "tačno"}, {"i", "i"},
    {"sat", "sat"}, {"sata", "sata"}, {"sati", "sati"}, {"minut", "minut"},
    {"minuta", "minuta"}};

static std::string izgovor(int h, int m) {
  std::string s;
  for (auto& k : sv::recenica(h, m)) s += (s.empty() ? "" : " ") + TEKST.at(k);
  return s;
}

int main() {
  struct { int h, m; const char* ocekivano; } slucajevi[] = {
      {0, 0, "Sada je tačno nula sati"},
      {1, 1, "Sada je jedan sat i jedan minut"},
      {2, 12, "Sada je dva sata i dvanaest minuta"},
      {4, 24, "Sada je četiri sata i dvadeset četiri minuta"},
      {11, 11, "Sada je jedanaest sati i jedanaest minuta"},
      {14, 23, "Sada je četrnaest sati i dvadeset tri minuta"},
      {21, 31, "Sada je dvadeset jedan sat i trideset jedan minut"},
      {22, 5, "Sada je dvadeset dva sata i pet minuta"},
      {23, 59, "Sada je dvadeset tri sata i pedeset devet minuta"},
  };
  int greske = 0;
  for (auto& c : slucajevi) {
    std::string s = izgovor(c.h, c.m);
    bool ok = s == c.ocekivano;
    greske += !ok;
    printf("%s %02d:%02d  %s\n", ok ? "OK  " : "FAIL", c.h, c.m, s.c_str());
  }
  // Svi isečci za ceo dan moraju postojati.
  for (int h = 0; h < 24; h++)
    for (int m = 0; m < 60; m++) izgovor(h, m);
  printf(greske ? "\n%d grešaka\n" : "\nSve OK (proverena sva 1440 vremena)\n", greske);
  return greske != 0;
}
