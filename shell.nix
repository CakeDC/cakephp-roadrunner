{ pkgs ? import (fetchTarball "https://github.com/NixOS/nixpkgs/archive/c8a17ce7abc03c50cd072e9e6c9b389c5f61836b.tar.gz") {} }:
with pkgs; let
    php = php81.withExtensions ({ enabled, all }: enabled);
in
    mkShell {
        buildInputs = [
            php
            php.packages.composer
        ];
    }
