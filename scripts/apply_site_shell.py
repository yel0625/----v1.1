from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

PAGE_KEYS = {
    "index.html": "home",
    "products.html": "products",
    "capsule-production.html": "capsule-production",
    "CNC-machining.html": "cnc-machining",
    "history.html": "history",
    "patents.php": "patents",
    "en/patents.html": "patents",
    "ru/patents.html": "patents",
    "information.html": "information",
    "contact.html": "contact",
    "about.html": "about",
    "tech-standards.html": "tech-standards",
    "industry-news.html": "industry-news",
    "tech-docs.html": "tech-docs",
    "capsule-standard.html": "capsule-standard",
    "en/index.html": "home",
    "en/products.html": "products",
    "en/history.html": "history",
    "en/information.html": "information",
    "en/contact.html": "contact",
    "en/about.html": "about",
    "en/tech-standards.html": "tech-standards",
    "en/industry-news.html": "industry-news",
    "en/tech-docs.html": "tech-docs",
    "en/cooperation.html": "cooperation",
    "en/tech-standards/capsule-standard.html": "capsule-standard",
    "ru/index.html": "home",
    "ru/products.html": "products",
    "ru/history.html": "history",
    "ru/information.html": "information",
    "ru/contact.html": "contact",
    "ru/about.html": "about",
    "ru/tech-standards.html": "tech-standards",
    "ru/industry-news.html": "industry-news",
    "ru/tech-docs.html": "tech-docs",
}

HEADER_RE = re.compile(r"<header>.*?</header>", re.S)
FOOTER_RE = re.compile(r"<footer>.*?</footer>", re.S)
BODY_RE = re.compile(r"<body(?P<attrs>[^>]*)>", re.S)
SITE_SHELL_RE = re.compile(r'\s*<script src="[^"]*site-shell\.js"></script>\s*', re.S)


def to_posix(path: Path) -> str:
    return path.relative_to(ROOT).as_posix()


def site_shell_path(relative_path: str) -> str:
    depth = len(Path(relative_path).parents) - 1
    return "../" * depth + "js/site-shell.js"


def replace_body(match: re.Match[str], page_key: str) -> str:
    attrs = re.sub(r'\sdata-page="[^"]*"', "", match.group("attrs"))
    return f'<body{attrs} data-page="{page_key}">'


def transform_html(path: Path) -> None:
    relative_path = to_posix(path)
    page_key = PAGE_KEYS.get(relative_path)
    if not page_key:
        return

    raw = path.read_bytes()
    newline = "\r\n" if b"\r\n" in raw else "\n"
    text = raw.decode("utf-8-sig")

    if text.startswith("DOCTYPE html>"):
        text = "<!" + text

    text = SITE_SHELL_RE.sub("\n", text)
    text = HEADER_RE.sub('<header data-site-header></header>', text, count=1)
    text = FOOTER_RE.sub('<footer data-site-footer></footer>', text, count=1)
    text = BODY_RE.sub(lambda match: replace_body(match, page_key), text, count=1)

    script_src = site_shell_path(relative_path)
    if "site-shell.js" not in text:
        main_js_pattern = re.compile(r'(?P<indent>\s*)<script src="[^"]*main\.js"></script>', re.S)
        match = main_js_pattern.search(text)
        if match:
            indent = match.group("indent") or "    "
            script_tag = f"{newline}{indent}<script src=\"{script_src}\"></script>{newline}"
            text = text[: match.start()] + script_tag + text[match.start() :]
        else:
            text = text.replace("</body>", f"{newline}    <script src=\"{script_src}\"></script>{newline}</body>")

    normalized = text.replace("\r\n", "\n").replace("\r", "\n")
    path.write_text(normalized.replace("\n", newline), encoding="utf-8", newline="")


def main() -> None:
    for relative_path in PAGE_KEYS:
        transform_html(ROOT / relative_path)


if __name__ == "__main__":
    main()
