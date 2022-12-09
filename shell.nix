{ pkgs ? import (fetchTarball "https://github.com/NixOS/nixpkgs/archive/c935f5e0add2cf0ae650d072c8357533e21b0c35.tar.gz") {} }:
with pkgs; let
    php = php80.withExtensions ({ enabled, all }: enabled);
in
    mkShell {
        buildInputs = [
            php
            php.packages.composer
        ];
    }
