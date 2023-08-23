// чтобы использовать нужно:
// 1) импортировать в компоненте import slideMixin from '~/mixins/slideMixin'
// 2) подключить миксин mixins: [slideMixin],
// 3) ипользовать <div @click="slideToggle($event.target)">triger</div> <p>content</p>
export default {
  methods: {
    slideUp(target, duration = 1500) {
      if (!target.nextElementSibling.classList.contains('_slide')) {
        target.classList.remove('active')
        target.nextElementSibling.classList.add('_slide')
        target.nextElementSibling.style.transitionProperty = 'height, margin, padding'
        target.nextElementSibling.style.transitionDuration = duration + 'ms'
        target.nextElementSibling.style.height = target.nextElementSibling.offsetHeight + 'px'
        // eslint-disable-next-line no-unused-expressions
        target.nextElementSibling.offsetHeight
        target.nextElementSibling.style.overflow = 'hidden'
        target.nextElementSibling.style.height = 0
        target.nextElementSibling.style.paddingTop = 0
        target.nextElementSibling.style.paddingBottom = 0
        target.nextElementSibling.style.marginTop = 0
        target.nextElementSibling.style.marginBottom = 0
        window.setTimeout(() => {
          target.nextElementSibling.hidden = true
          target.nextElementSibling.style.removeProperty('height')
          target.nextElementSibling.style.removeProperty('padding-top')
          target.nextElementSibling.style.removeProperty('padding-bottom')
          target.nextElementSibling.style.removeProperty('margin-top')
          target.nextElementSibling.style.removeProperty('margin-bottom')
          target.nextElementSibling.style.removeProperty('overflow')
          target.nextElementSibling.style.removeProperty('transition-duration')
          target.nextElementSibling.style.removeProperty('transition-property')
          target.nextElementSibling.classList.remove('_slide')
        }, duration)
      }
    },
    slideDown(target, duration = 500) {
      if (!target.nextElementSibling.classList.contains('_slide')) {
        target.classList.add('active')
        target.nextElementSibling.classList.add('_slide')
        if (target.nextElementSibling.hidden) {
          target.nextElementSibling.hidden = false
        }
        const height = target.nextElementSibling.offsetHeight
        target.nextElementSibling.style.overflow = 'hidden'
        target.nextElementSibling.style.height = 0
        target.nextElementSibling.style.paddingTop = 0
        target.nextElementSibling.style.paddingBottom = 0
        target.nextElementSibling.style.marginTop = 0
        target.nextElementSibling.style.marginBottom = 0
        // eslint-disable-next-line no-unused-expressions
        target.nextElementSibling.offsetHeight
        target.nextElementSibling.style.transitionProperty = 'height, margin, padding'
        target.nextElementSibling.style.transitionDuration = duration + 'ms'
        target.nextElementSibling.style.height = height + 'px'
        target.nextElementSibling.style.removeProperty('padding-top')
        target.nextElementSibling.style.removeProperty('padding-bottom')
        target.nextElementSibling.style.removeProperty('margin-top')
        target.nextElementSibling.style.removeProperty('margin-bottom')
        window.setTimeout(() => {
          target.nextElementSibling.style.removeProperty('height')
          target.nextElementSibling.style.removeProperty('overflow')
          target.nextElementSibling.style.removeProperty('transition-duration')
          target.nextElementSibling.style.removeProperty('transition-property')
          target.nextElementSibling.classList.remove('_slide')
        }, duration)
      }
    },
    slideToggle(target, duration = 500) {
      if (target.nextElementSibling.hidden) {
        return this.slideDown(target, duration)
      } else {
        return this.slideUp(target, duration)
      }
    },
  },
}
