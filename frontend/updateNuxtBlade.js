// const fs = require('fs');
// const path = require('path');

// const htmlPath = path.join(__dirname, '..', 'public', 'assets', 'index.html');
// const templatePath = path.join(__dirname, '..', 'resources', 'views', 'front', 'home', 'nuxt-template.blade.php');
// const outputPath = path.join(__dirname, '..', 'resources', 'views', 'front', 'home', 'frontend.blade.php');

// const htmlContent = fs.readFileSync(htmlPath, 'utf-8');

// const cssMatches = htmlContent.match(/<link.*?href="\/_nuxt\/(.*?\.css)".*?>/g) || [];
// const jsMatches = htmlContent.match(/<script.*?src="\/_nuxt\/(.*?\.js)".*?><\/script>/g) || [];

// const cssLinks = cssMatches.join('\n');
// const jsLinks = jsMatches.join('\n');

// let template = fs.readFileSync(templatePath, 'utf-8');
// template = template.replace('<!-- CSS_LINKS -->', cssLinks);
// template = template.replace('<!-- JS_LINKS -->', jsLinks);

// fs.writeFileSync(outputPath, template);

// console.log("frontend.blade.php has been updated!");
const fs = require('fs');
const path = require('path');

// Определите пути к исходному и целевому файлам
const sourcePath = path.resolve(__dirname, '../public/assets/index.html');
const targetPath = path.resolve(__dirname, '../resources/views/front/home/frontend.blade.php');

// Перемещение файла
fs.rename(sourcePath, targetPath, (err) => {
  if (err) {
    console.error('Ошибка при перемещении файла:', err);
  } else {
    console.log(`Файл успешно перемещен в ${targetPath}`);
  }
});
const fileContent = fs.readFileSync(targetPath, 'utf8');
const updatedContent = fileContent.replace(/\/app\//g, '/public/assets/app/');
fs.writeFileSync(targetPath, updatedContent);

