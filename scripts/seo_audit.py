"""Audit public sitemap URLs against a local preview server."""

from __future__ import annotations

import collections
import sys
import urllib.parse
import urllib.request
import xml.etree.ElementTree as ET

from lxml import html


def first(values: list[str]) -> str:
    return " ".join(values).strip()


def main() -> int:
    sitemap_path = sys.argv[1]
    preview_origin = sys.argv[2].rstrip("/") if len(sys.argv) > 2 else "http://127.0.0.1:8080"
    root = ET.parse(sitemap_path).getroot()
    ns = {"s": "http://www.sitemaps.org/schemas/sitemap/0.9"}
    public_urls = [node.text or "" for node in root.findall("s:url/s:loc", ns)]
    rows = []

    for public_url in public_urls:
        parts = urllib.parse.urlsplit(public_url)
        local_url = preview_origin + parts.path + ("?" + parts.query if parts.query else "")
        try:
            response = urllib.request.urlopen(local_url)
            document = html.fromstring(response.read())
            images = document.xpath("//img")
            rows.append({
                "url": public_url,
                "status": response.status,
                "title": first(document.xpath("//title/text()")),
                "description": first(document.xpath("//meta[@name='description']/@content")),
                "canonical": first(document.xpath("//link[@rel='canonical']/@href")),
                "h1": len(document.xpath("//h1")),
                "missing_alt": sum(not (image.get("alt") or "").strip() for image in images),
                "lang": document.get("lang") or "",
                "links": [urllib.parse.urljoin(public_url, href) for href in document.xpath("//a[@href]/@href")],
            })
        except Exception as error:  # audit should report and continue
            rows.append({"url": public_url, "status": getattr(error, "code", "ERR"), "title": "", "description": "", "canonical": "", "h1": 0, "missing_alt": 0, "lang": "", "links": []})

    print(f"TOTAL {len(rows)}")
    issue_count = 0
    for row in rows:
        issues = []
        if row["status"] != 200:
            issues.append(f"HTTP={row['status']}")
        if not row["title"]:
            issues.append("NO_TITLE")
        if not row["description"]:
            issues.append("NO_DESC")
        if not row["canonical"]:
            issues.append("NO_CANONICAL")
        if row["h1"] != 1:
            issues.append(f"H1={row['h1']}")
        if row["missing_alt"]:
            issues.append(f"ALT_MISSING={row['missing_alt']}")
        if not row["lang"]:
            issues.append("NO_LANG")
        if issues:
            issue_count += 1
            print("ISSUE", " | ".join(issues), "|", row["url"])

    for field in ("title", "description"):
        counts = collections.Counter(row[field] for row in rows if row[field])
        for value, count in counts.items():
            if count > 1:
                issue_count += 1
                print(f"DUP_{field.upper()}", count, value[:160])

    checked_links = set()
    for row in rows:
        for link in row["links"]:
            parts = urllib.parse.urlsplit(link)
            if parts.hostname not in {"www.gsqilin.cn", "gsqilin.cn"} or link in checked_links:
                continue
            checked_links.add(link)
            local_url = preview_origin + parts.path + ("?" + parts.query if parts.query else "")
            try:
                status = urllib.request.urlopen(local_url).status
            except Exception as error:
                status = getattr(error, "code", "ERR")
            if status >= 400 if isinstance(status, int) else True:
                issue_count += 1
                print("BROKEN_LINK", status, link)

    print(f"LINKS_CHECKED {len(checked_links)}")
    print(f"ISSUES {issue_count}")
    return 1 if issue_count else 0


if __name__ == "__main__":
    raise SystemExit(main())
