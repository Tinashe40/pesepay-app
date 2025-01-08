{ pkgs ? import <nixpkgs> {} }:

pkgs.mkShell {
  buildInputs = [
    pkgs.php
    pkgs.phpPackages.composer
    pkgs.postgresql
    pkgs.git
  ];

  shellHook = ''
    echo "Development environment ready for Pesepay App!"
  '';
}
