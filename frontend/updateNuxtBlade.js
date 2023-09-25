const fs = require('fs');
const path = require('path');

const sourcePath = path.resolve(__dirname, '../public/assets/200.html');
const targetPath = path.resolve(__dirname, '../resources/views/front/home/frontend.blade.php');

// Копирование файла
fs.copyFile(sourcePath, targetPath, (err) => {
    if (err) {
        console.error('Ошибка при копировании файла:', err);
        return;
    }

    console.log(`Файл успешно скопирован в ${targetPath}`);

    // Теперь можно безопасно читать и изменять целевой файл
    const fileContent = fs.readFileSync(targetPath, 'utf8');
    const updatedContent = fileContent.replace(/\/app\//g, '/public/assets/app/');
    fs.writeFileSync(targetPath, updatedContent);
});
