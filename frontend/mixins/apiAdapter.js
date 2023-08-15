import axios from 'axios'

const isSSR = context.store.state['checkMode/mode']

export default function fetchData(url) {
  return isSSR ? asyncData(url) : createdData(url)
}

const asyncData = async (url) => {
  return await axios.get(url)
    .then(response => response.data)
    .catch(error => {
      console.error('Error fetching data:', error);
      return null;
    });
}
const createdData = async (url) => {
  return await axios.get(url)
    .then(response => response.data)
    .catch(error => {
      console.error('Error fetching data:', error);
      return null;
    });
}
