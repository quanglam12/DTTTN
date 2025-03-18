function renderContent(data) {
    const contentDiv = document.getElementById('chitietbaiviet');

    data.blocks.forEach(block => {
        let htmlElement = '';

        switch (block.type) {
            case 'header':
                htmlElement = `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
                break;
            case 'paragraph':
                htmlElement = `<p>${block.data.text}</p>`;
                break;
            case 'image':
                htmlElement = `<img src="${block.data.file.url}" alt="${block.data.caption}"><p style="text-align: center; font-style: italic;">${block.data.caption}</p>`;
                break;
            case 'list':
                const listTag = block.data.style === 'unordered' ? 'ul' : 'ol';
                htmlElement = `<${listTag}>${block.data.items.map(item => `<li>${item.content}</li>`).join('')}</${listTag}>`;
                break;
            case 'quote':
                htmlElement = `<blockquote>${block.data.text}<br><i>${block.data.caption}</i></blockquote>`;
                break;
            case 'code':
                htmlElement = `<pre><code>${block.data.code}</code></pre>`;
                break;
            case 'table':
                htmlElement = `<table>${block.data.content.map(row => `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`).join('')}</table>`;
                break;
            case 'warning':
                htmlElement = `<div class="warning"><strong>${block.data.title}</strong><p>${block.data.message}</p></div>`;
                break;
            case 'delimiter':
                htmlElement = `<div class="delimiter">* * *</div>`;
                break;
            default:
                htmlElement = `<p>[Không hỗ trợ hiển thị loại: ${block.type}]</p>`;
        }

        contentDiv.innerHTML += htmlElement;
    });
}
