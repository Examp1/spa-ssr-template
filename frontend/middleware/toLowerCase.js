// middleware/toLowerCase.js
export default function ({ route, redirect }) {
  const path = route.fullPath;
  const lowerCasePath = path.toLowerCase();

  if (path !== lowerCasePath) {
    redirect(301, lowerCasePath);
  }
}
