var method = MergeBuildProperties.prototype;

/**
 * Merges given json files
 *
 * @param defaultFile Filename
 * @param overWriteFile Filename
 * @constructor
 */
function MergeBuildProperties(defaultFile, overWriteFile) {
    this.defaultFile = require(__dirname + '/' + defaultFile);
    this.overWriteFilePath = __dirname + '/' + overWriteFile;
}

/**
 * Merge the files and return JavaScript object
 * @returns {object}
 */
method.merge = function() {
    var buildProperties = this.defaultFile;
    var fs = require('fs');
    if (fs.existsSync(this.overWriteFilePath)) {
        var optionalBuildProperties = require(this.overWriteFilePath);
        buildProperties = JSON.parse((JSON.stringify(buildProperties) + JSON.stringify(optionalBuildProperties)).replace(/}{/g,","));
    }else{
        console.info('\n\n----------------------------\n');
        console.info('Probably you should create the ' + this.overWriteFilePath +' to overwrite the default settings');
        console.info('\n----------------------------\n\n');
    }
    return buildProperties;
};


module.exports = MergeBuildProperties;