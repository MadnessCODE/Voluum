// Pretvara sat i minut u niz zvučnih isečaka na srpskom, uz pravilnu gramatiku.
// Primer: 14:23 -> sada_je 10 4 sati i 20 3 minuta
//         ("Sada je četrnaest sati i dvadeset tri minuta")
#pragma once
#include <string>
#include <vector>

namespace sv {

enum Oblik { JEDNINA, PAUKAL, MNOZINA };

// 1, 21, 31... (osim 11)        -> jednina  (sat, minut)
// 2-4, 22-24... (osim 12-14)    -> paukal   (sata, minuta)
// ostalo                        -> množina  (sati, minuta)
inline Oblik oblik(int n) {
  int d = n % 10, s = n % 100;
  if (d == 1 && s != 11) return JEDNINA;
  if (d >= 2 && d <= 4 && !(s >= 12 && s <= 14)) return PAUKAL;
  return MNOZINA;
}

// Broj 0..59 -> isečci. Postoje snimci za 0..20, 30, 40, 50.
inline void broj(int n, std::vector<std::string>& out) {
  if (n <= 20) {
    out.push_back(std::to_string(n));
    return;
  }
  out.push_back(std::to_string(n / 10 * 10));
  if (n % 10) out.push_back(std::to_string(n % 10));
}

inline std::vector<std::string> recenica(int sat, int minut) {
  std::vector<std::string> v{"sada_je"};
  if (minut == 0) v.push_back("tacno");
  broj(sat, v);
  Oblik os = oblik(sat);
  v.push_back(os == JEDNINA ? "sat" : os == PAUKAL ? "sata" : "sati");
  if (minut > 0) {
    v.push_back("i");
    broj(minut, v);
    v.push_back(oblik(minut) == JEDNINA ? "minut" : "minuta");
  }
  return v;
}

}  // namespace sv
