# Bundled ARM64 executable

Unmodified `usr/local/bin/wkhtmltopdf` from the official patched-Qt package:

- Version: wkhtmltopdf 0.12.6.1, packaging iteration 2, Ubuntu 22.04 ARM64.
- Package: https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-2/wkhtmltox_0.12.6.1-2.jammy_arm64.deb
- Package SHA-256: `17167a87bf59ea9a24cc0e8321ea27fbb17d6a915f445c52b8909f2539b673c1`
- Executable SHA-256: `00fbba5e1ee392776c2978b45503c07af34c42086c842b31dc9d7a76d52fcf29`

The checksums were calculated from the downloaded upstream artifact. The ELF
requires AArch64, glibc 2.34 or newer, OpenSSL 3, libjpeg.so.8, libpng16,
fontconfig, FreeType, X11, Xrender, libstdc++ and zlib. Fonts must be installed.
Qt is statically linked; the other libraries remain system dependencies.

Unlike wkhtmltopdf 0.12.5, 0.12.6.1 disables local-file access by default.
HTTP/HTTPS body assets and header/footer URLs remain supported. Test existing
templates before rollout; do not enable unrestricted local-file access as a
default workaround.

## Source and licenses

The PHP wrapper uses the root MIT license. The executable is distributed under
LGPLv3; `COPYING.LESSER` and `COPYING` contain its license terms. The bundled Qt
license texts and exception are included alongside them. The executable is
unchanged and can be replaced with a compatible build at the same path.

Upstream source and build recipes for the 0.12.6.1 release:

- wkhtmltopdf: https://github.com/wkhtmltopdf/wkhtmltopdf/tree/c61ecdeed13b8e001f77c4a2b4050d0c732e9e72
- Patched Qt submodule: https://github.com/wkhtmltopdf/qt/tree/c8a847338fac96fed89f40116d88468440891099
- Packaging: https://github.com/wkhtmltopdf/packaging/tree/0.12.6.1-2
- Upstream release: https://github.com/wkhtmltopdf/packaging/releases/tag/0.12.6.1-2

The packaging workflow builds `jammy-arm64` from the `0.12.6.x` source branch
with its Qt submodule. To reproduce extraction, verify the package checksum,
run `dpkg-deb --extract` into a temporary directory, copy
`usr/local/bin/wkhtmltopdf`, preserve executable permissions, and verify the
executable checksum above.
