#!/usr/bin/env bash

targetDir=$HOME/.local/bin
targetFilePath=$HOME/.local/bin/pq

# first make sure the target dir exists
mkdir -p "$targetDir"

echo "installing pq..."
curl -sSL https://github.com/neblabs/pq/releases/latest/download/pq.phar -o "$targetFilePath"

sudo chmod +x "$targetFilePath"

# warn if not in path
if ! [[ "$targetDir" == *"/.local/bin"* ]]; then
    echo [warn] Installed to "$targetFilePath" but it "doesn't" seem to be in your PATH.
fi
