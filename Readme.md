# EmberChatAppServer #


### Dependencies ###

- [appserver.io](http://appserver.io/)
- [node.js](http://nodejs.org/) (for build)

### Build ###

You need node.js for building this project.
```
npm install
grunt
```

### Development ###

There is a `deploy` task and a watching deploy task `server`. Probably you need to add `--force` for overwriting
external files.

The appserver needs a restart after every change with root rights. For this purpose there is a separate node.js
script you can use.
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