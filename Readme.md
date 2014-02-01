# EmberChatAppServer #


### Dependencies ###

- [appserver.io](http://appserver.io/)
- [EmberChat](https://github.com/mwitte/EmberChat) (for build)
- [node.js](http://nodejs.org/) (for build)

### Build ###

You need node.js and the appshimp of the EmberChat project for building this project.

**Make sure** you created a `buildProperties.json` to specify the location for the appshim!
```
{
    "app": "/workspace/emberchat/dist"
}
```
In future there will be probably a fallback. After just build..
```
npm install
grunt
```

### Development ###

There is also a `deploy` task and a watching deploy task `server`. Probably you need to add `--force` for overwriting external files.

The appserver needs a restart after every change with root rights. For this purpose there is a separate node.js script you can use.
```
sudo node RestartListener.js
```