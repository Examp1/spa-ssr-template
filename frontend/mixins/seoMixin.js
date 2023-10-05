import Vue from 'vue'

export const seoMixin = {
    head() {
        // Проверка на наличие this.meta
        if (!this.meta) return {}

        // Установка lang по умолчанию
        let langAttribute = 'uk-UA'

        // Проверяем текущую локаль (предполагая, что вы используете nuxt-i18n или другой плагин)
        if (this.$i18n && this.$i18n.locale === 'en') {
            langAttribute = 'en-UA'
        }

        // Деструктуризация для удобства
        const {
            title = 'OwlWeb',
            description,
            favicon,
            og: {
                title: ogTitle,
                description: ogDescription,
                image: ogImage,
                url: ogUrl,
                type: ogType,
                locale: ogLocale,
                site_name: ogSiteName,
            } = {},
            script,
            schema,
        } = this.meta

        // Определение базового URL
        const baseUrl = this.meta.og.url.match(/^(https?:\/\/[^/]+)/)[1]
        const cleanedPath = this.$route.fullPath.replace(
            /^\/(en|ru)(\/|$)/,
            '/'
        )
        // Создание массива links
        const links = [
            {
                rel: 'icon',
                type: 'image/x-icon',
                href: favicon,
            },
            { rel: 'canonical', href: `${baseUrl}${this.$route.path}` },
            {
                rel: 'alternate',
                hreflang: 'uk',
                href: `${baseUrl}${cleanedPath}`,
            },
            {
                rel: 'alternate',
                hreflang: 'en',
                href: `${baseUrl}/en${cleanedPath}`,
            },
        ]

        // Проверка на наличие пагинации и добавление соответствующих тегов
        if (this.$route.query.page && this.$route.query.page > 1) {
            const prevPage =
                this.products.paginate.prev_page_url?.split('page=')[1]
            const nextPage =
                this.products.paginate.next_page_url?.split('page=')[1] ||
                this.products.paginate.last_page_url.split('page=')[1]
            links.push(
                {
                    rel: 'prev',
                    href: `${baseUrl}${this.$route.path}?page=${prevPage}`,
                },
                {
                    rel: 'next',
                    href: `${baseUrl}${this.$route.path}?page=${nextPage}`,
                }
            )
        }

        // Попытка парсинга shema и обработка ошибки
        let parsedShema = ''
        try {
            parsedShema = schema ? JSON.parse(schema) : ''
        } catch (error) {
            console.warn('Error parsing schema:', error)
        }

        return {
            htmlAttrs: {
                lang: langAttribute, // Установка атрибута lang
            },
            title,
            meta: [
                {
                    hid: 'description',
                    name: 'description',
                    content: description,
                },
                { hid: 'og:title', property: 'og:title', content: ogTitle },
                {
                    hid: 'og:description',
                    property: 'og:description',
                    content: ogDescription,
                },
                { hid: 'og:image', property: 'og:image', content: ogImage },
                { hid: 'og:url', property: 'og:url', content: ogUrl },
                { hid: 'og:type', property: 'og:type', content: ogType },
                { hid: 'og:locale', property: 'og:locale', content: ogLocale },
                {
                    hid: 'og:site_name',
                    property: 'og:site_name',
                    content: ogSiteName,
                },
            ],
            link: links,
            script: [
                { innerHTML: script, type: 'application/ld+json' },
                { type: 'application/ld+json', json: parsedShema },
            ],
            __dangerouslyDisableSanitizers: ['script'],
        }
    },
}

Vue.mixin(seoMixin)
