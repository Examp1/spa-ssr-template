import Vue from 'vue'
Vue.filter('zeroPad', function (value) {
  if (typeof value !== 'number') {
    return value;
  }

  return value < 10 ? '0' + value : value.toString();
});
