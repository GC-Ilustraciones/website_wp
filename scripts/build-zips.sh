#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
output_dir="$repo_root/dist"
mkdir -p "$output_dir"

count=0
for kind in themes plugins; do
  for component in "$repo_root/$kind"/*/; do
    [[ -d "$component" ]] || continue
    slug="$(basename "$component")"
    archive="$output_dir/${kind}-${slug}.zip"
    rm -f "$archive"
    (
      cd "$repo_root/$kind"
      zip -q -r "$archive" "$slug" \
        -x "*/.DS_Store" "*/node_modules/*" "*/.git/*"
    )
    printf 'Creado: %s\n' "$archive"
    ((count += 1))
  done
done

if ((count == 0)); then
  printf 'Aún no hay temas ni plugins propios para empaquetar.\n'
fi
