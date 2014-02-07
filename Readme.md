# EmberChatAppServer #

This is a chat server for [EmberChat](https://github.com/mwitte/EmberChat) based on
[appserver.io](http://appserver.io/)

Currently under heavy development.

### Dependencies ###

- [appserver.io](http://appserver.io/) as server
- [node.js](http://nodejs.org/) for building

### Install ###

Add this application as webapp to the http container vhosts in the `appserver.xml`
```
<vhost name="emberchat.dev" appBase="/emberchat" />
```
Build and deployment:
```
npm install
grunt deploy
```
Make sure that you added `emberchat.dev` to your local system vhosts.
Open `http://emberchat.dev:8586/` and start chatting ;)

### Development ###

There is a `grunt deploy` task and a watching deploy task `grunt server`.
Probably you need to add `--force` for overwriting external files.

The appserver needs a restart after every change with root rights. For this purpose there
is a separate node.js script you can use.
```
sudo node build/RestartListener.js
```

You should create a `build/buildProperties.json` to specify the location for your local app. This disables the
download fallback which makes everything faster.
```
{
    "app": "/workspace/emberchat/dist"
}
```