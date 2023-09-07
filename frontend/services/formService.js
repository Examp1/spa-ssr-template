// services/formService.js

export async function submitForm(axios, formData) {
  try {
    const response = await axios.$post('/api/request/send', formData);
    return { success: true, data: response.data };
  } catch (error) {
    return { success: false, error: error.response ? error.response.data : 'Server error' };
  }
}
