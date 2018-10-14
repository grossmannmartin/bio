TARGET_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )/../data/Keys"

ssh-keygen -t rsa -b 4096 -f "$TARGET_DIR/private"
openssl rsa -in "$TARGET_DIR/private" -pubout -outform PEM -out "$TARGET_DIR/public"
rm "$TARGET_DIR/private.pub"
