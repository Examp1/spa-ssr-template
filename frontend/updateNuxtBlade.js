import fs from 'fs/promises';
import { fileURLToPath } from 'url';
import path from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const sourcePath = path.resolve(__dirname, './.output/public/index.html');
const targetPath = path.resolve(__dirname, '../resources/views/front/home/frontend.blade.php');

const copyAndUpdateFile = async () => {
  try {
    await fs.copyFile(sourcePath, targetPath);
    console.log(`Файл успешно скопирован в ${targetPath}`);

    const fileContent = await fs.readFile(targetPath, 'utf8');
    const updatedContent = fileContent.replace(/\/app\//g, '/public/assets/app/');
    await fs.writeFile(targetPath, updatedContent);
  } catch (err) {
    console.error('Ошибка при копировании файла:', err);
  }
};

copyAndUpdateFile();
