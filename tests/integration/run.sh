#!/bin/sh
set -eu

output_directory=$1
test_directory=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
scratch_directory=$(mktemp -d /tmp/html-to-pdf-test.XXXXXX)
server_pid=

cleanup() {
    if [ -n "$server_pid" ]; then
        kill "$server_pid" 2>/dev/null || true
        wait "$server_pid" 2>/dev/null || true
    fi
    rm -rf "$scratch_directory"
}
trap cleanup EXIT HUP INT TERM

unset DISPLAY
export XDG_CONFIG_HOME="$scratch_directory/config"
export XDG_CACHE_HOME="$scratch_directory/cache"
export XDG_RUNTIME_DIR="$scratch_directory/runtime"
mkdir -m 700 "$XDG_CONFIG_HOME" "$XDG_CACHE_HOME" "$XDG_RUNTIME_DIR"

php -S 127.0.0.1:8080 -t "$test_directory/fixtures" >"$scratch_directory/server.log" 2>&1 &
server_pid=$!
php -r '
for ($attempt = 0; $attempt < 50; $attempt++) {
    $socket = @fsockopen("127.0.0.1", 8080, $errno, $error, 0.1);
    if ($socket) {
        fclose($socket);
        exit(0);
    }
    usleep(100000);
}
fwrite(STDERR, "Fixture server did not start\n");
exit(1);
'

php "$test_directory/render.php" "$output_directory"
