define(["require","exports","sass"],function(e,t,n){var r=function(){function t(){this.scssPath="../styles/scss",n.setWorkerUrl(e.toUrl(t.workerUrl)),this.sass=new n}return t.prototype.compile=function(e,t){var n="";e.forEach(function(e){n+=$('div.scss_file_content[data-scss-file="'+e+'"]').text()+"\n"}),this.sass.compile(n,t)},t.workerUrl="../plugins/sass.js/dist/sass.worker.js",t}();return r});