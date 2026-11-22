#!/usr/bin/env python3
"""Generiše zvučne isečke (WAV, 16 kHz, mono, 16-bit) u folder data/.

Lep, prirodan glas (Microsoft neural, treba internet):
    pip install edge-tts
    python tools/generisi_glas.py                       # muški glas (Nicholas)
    python tools/generisi_glas.py --glas sr-RS-SophieNeural   # ženski glas

Robotski glas bez interneta (espeak-ng):
    python tools/generisi_glas.py --motor espeak

Svoj snimljeni glas: snimi fajlove sa istim imenima (npr. 14.wav, sati.wav)
i propusti ih kroz:  python tools/generisi_glas.py --samo-obradi folder_sa_snimcima

Potreban je ffmpeg u PATH-u.
"""
import argparse
import asyncio
import pathlib
import shutil
import subprocess
import sys
import tempfile

TEKST = {
    "0": "nula", "1": "jedan", "2": "dva", "3": "tri", "4": "četiri",
    "5": "pet", "6": "šest", "7": "sedam", "8": "osam", "9": "devet",
    "10": "deset", "11": "jedanaest", "12": "dvanaest", "13": "trinaest",
    "14": "četrnaest", "15": "petnaest", "16": "šesnaest", "17": "sedamnaest",
    "18": "osamnaest", "19": "devetnaest", "20": "dvadeset", "30": "trideset",
    "40": "četrdeset", "50": "pedeset",
    "sada_je": "Sada je", "tacno": "tačno", "i": "i",
    "sat": "sat", "sata": "sata", "sati": "sati",
    "minut": "minut.", "minuta": "minuta.",
    "nema_vremena": "Vreme još nije podešeno. Proverite vaj faj.",
}

DATA = pathlib.Path(__file__).resolve().parent.parent / "data"

# Skida tišinu sa početka i kraja, normalizuje jačinu, 16 kHz mono 16-bit.
FILTER = (
    "silenceremove=start_periods=1:start_threshold=-45dB,"
    "areverse,silenceremove=start_periods=1:start_threshold=-45dB,areverse,"
    "loudnorm=I=-16:TP=-1.5,apad=pad_dur=0.04"
)


def obradi(ulaz: pathlib.Path, izlaz: pathlib.Path) -> None:
    subprocess.run(
        ["ffmpeg", "-y", "-loglevel", "error", "-i", str(ulaz), "-af", FILTER,
         "-ar", "16000", "-ac", "1", "-sample_fmt", "s16", "-map_metadata", "-1",
         "-fflags", "+bitexact", str(izlaz)],
        check=True,
    )


async def edge(tekst: str, glas: str, fajl: pathlib.Path) -> None:
    import edge_tts
    await edge_tts.Communicate(tekst, glas, rate="-5%").save(str(fajl))


def espeak(tekst: str, fajl: pathlib.Path) -> None:
    subprocess.run(["espeak-ng", "-v", "sr", "-s", "140", "-w", str(fajl), tekst],
                   check=True)


def main() -> None:
    p = argparse.ArgumentParser()
    p.add_argument("--motor", choices=["edge", "espeak"], default="edge")
    p.add_argument("--glas", default="sr-RS-NicholasNeural")
    p.add_argument("--samo-obradi", metavar="FOLDER",
                   help="obradi postojeće snimke umesto generisanja")
    a = p.parse_args()

    if not shutil.which("ffmpeg"):
        sys.exit("Nedostaje ffmpeg.")
    DATA.mkdir(exist_ok=True)

    with tempfile.TemporaryDirectory() as tmp:
        for ime, tekst in TEKST.items():
            if a.samo_obradi:
                nadjeni = list(pathlib.Path(a.samo_obradi).glob(f"{ime}.*"))
                if not nadjeni:
                    print(f"  NEDOSTAJE {ime}")
                    continue
                sirov = nadjeni[0]
            elif a.motor == "edge":
                sirov = pathlib.Path(tmp) / f"{ime}.mp3"
                asyncio.run(edge(tekst, a.glas, sirov))
            else:
                sirov = pathlib.Path(tmp) / f"{ime}.wav"
                espeak(tekst, sirov)
            obradi(sirov, DATA / f"{ime}.wav")
            print(f"  {ime:13s} {tekst}")

    ukupno = sum(f.stat().st_size for f in DATA.glob("*.wav"))
    print(f"\nGotovo: {DATA}  ({ukupno / 1024:.0f} KB)")


if __name__ == "__main__":
    main()
