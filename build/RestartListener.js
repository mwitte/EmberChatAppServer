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

// LOAD AND MERGE BUILD PROPERTIES
var MergeBuildPropertiesClass = require('./MergeBuildProperties');
var propertyMerger = new MergeBuildPropertiesClass('buildDefaultProperties.json', 'buildProperties.json');
var buildProperties = propertyMerger.merge();

function puts(error, stdout, stderr) { sys.puts(stdout) }

/**
 * Watch the directory
 */
fs.watchFile(buildProperties.deploydir, function (curr, prev) {
    console.log(curr.mtime + 'Restarting Appserver.. ');
    // restart the server
    exec("/opt/appserver/sbin/appserverctl restart", puts);
});