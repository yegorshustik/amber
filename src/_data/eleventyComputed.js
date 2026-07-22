module.exports = {
  permalink: function(data) {
    // Keep exactly the same file names and paths
    return data.page.filePathStem + ".html";
  }
};
