# Do nothing but load the rc file, if present.
if [ -r "$HOME/.bashrc" ]; then
    . "$HOME/.bashrc"
fi

# Include .profile if it exists
if [ -r "$HOME/.profile" ]; then
. "$HOME/.profile"
fi
