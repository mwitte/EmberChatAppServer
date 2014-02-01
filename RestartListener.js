/**
 * RestartListener
 *
 * This script is very useful to automatically restart the appserver. It watches
 * the `dist` folder for changes and restarts the appserver if changed. The appserver
 * needs root rights for restarting.
 *
 * This script could included into the default grunt tasks but you shouldn't run grunt
 * with root rights!
 *
 * Just run this script with root rights like `sudo node RestartListener.js`
 */

var fs = require('fs');
var sys = require('sys');
var exec = require('child_process').exec;

function puts(error, stdout, stderr) { sys.puts(stdout) }

fs.watchFile('./dist', function (curr, prev) {
    console.log(curr.mtime + 'Restarting Appserver.. ');
    exec("/opt/appserver/sbin/appserverctl restart", puts);
});