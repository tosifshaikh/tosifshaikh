export default {
    methods: {
        makeParagraph(obj) {
            return `<p> ${obj.data.text}
            </p>`;
        },
        makeImage(obj) {
            const caption = obj.data.caption ? `<div><p>${obj.data.caption}</p></div>` : '';
            return `<div><img src="${obj.data.file.url}" alt="${obj.data.caption}"/>
                ${caption}
            </div>`;
        },
        makeHeader(obj) {
            return `<h${obj.data.level}>${obj.data.text}</h${obj.data.level}>`;
        },
        makeList(obj) {
            if (obj.data.style === 'unordered') {
                const list = obj.data.items.map(item => {
                    return `<li>${item}</li>`;
                });
                return `<ul>${list.join('')}</ul>`;

            }
            if (obj.data.style === 'ordered') {
                const list = obj.data.items.map(item => {
                    return `<li>${item}</li>`;
                });
                return `<ol>${list.join('')}</ol>`;

            }

        },
        makeQuote(obj) {
            return `<div><blockquote><p>${obj.data.text}</p></blockquote><p>${obj.data.caption}</p></div>`;
        },
        makeCode(obj) {

        },
        makeWarning(obj) {

        },
        makeDelimeter(obj) {

        },


    }
}
