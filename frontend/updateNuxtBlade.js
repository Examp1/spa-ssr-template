
const fs = require('fs');
const path = require('path');

// Определите пути к исходному и целевому файлам
const sourcePath = path.resolve(__dirname, '../public/assets/200.html');
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

