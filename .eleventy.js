module.exports = function(eleventyConfig) {
  // Pass through assets and scripts
  eleventyConfig.addPassthroughCopy("src/assets");
  eleventyConfig.addPassthroughCopy("src/*.js"); // like toc.js
  
  return {
    dir: {
      input: "src",
      output: "site",
      includes: "_includes",
      layouts: "_includes/layouts"
    },
    templateFormats: ["html", "njk", "md"],
    htmlTemplateEngine: "njk",
    markdownTemplateEngine: "njk"
  };
};
