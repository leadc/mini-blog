import './bootstrap';

import Alpine from 'alpinejs';
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Embed from '@editorjs/embed';
import ImageTool from '@editorjs/image';

window.Alpine = Alpine;

Alpine.start();

window.initEditor = (data = null) => {
    if (window.editorInstance) return;
    let previousImages = [];
    window.editorInstance = new EditorJS({
        holder: 'editorjs',
        tools: {
            header: Header,
            list: List,
            embed: {
                class: Embed,
                config: {
                    services: {
                        youtube: true,
                    }
                }
            },
            image: {
                class: ImageTool,
                config: {
                    endpoints: {
                        byFile: '/posts/upload-image',
                        byUrl: '/posts/upload-image',
                    },
                    field: 'image',
                    additionalRequestHeaders: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            },
        },
        i18n: {
            messages: {
                ui: {
                    "blockTunes": {
                        "toggler": {
                            "Click to tune": "Haz clic para ajustar",
                            "or drag to move": "o arrastra para mover"
                        },
                    },
                    "inlineToolbar": {
                        "converter": "Convertir"
                    },
                    "toolbar": {
                        "toolbox": {
                            "Add": "Agregar"
                        }
                    }
                },
                toolNames: {
                    "Text": "Texto",
                    "Heading": "Encabezado",
                    "List": "Lista",
                    "Warning": "Advertencia",
                    "Checklist": "Checklist",
                    "Quote": "Cita",
                    "Code": "Código",
                    "Delimiter": "Delimitador",
                    "Raw HTML": "HTML",
                    "Table": "Tabla",
                    "Link": "Enlace",
                    "Marker": "Marcador",
                    "Bold": "Negrita",
                    "Italic": "Cursiva",
                    "Image": "Imagen",
                    "Embed": "Video"
                },
                tools: {
                    "warning": {
                        "Title": "Título",
                        "Message": "Mensaje",
                    },
                    "link": {
                        "Add a link": "Agregar un enlace"
                    },
                    "stub": {
                        "The block can not be displayed": "No se puede mostrar el bloque"
                    },
                    "image": {
                        "Caption": "Pie de foto",
                        "Select an Image": "Selecciona una imagen",
                        "With border": "Con borde",
                        "Stretch image": "Expandir imagen",
                        "With background": "Con fondo"
                    },
                    "embed": {
                        "Enter a link": "Pega un enlace de YouTube, Twitter, Facebook, Instagram, etc."
                    }
                },
                blockTunes: {
                    "delete": {
                        "Delete": "Eliminar"
                    },
                    "moveUp": {
                        "Move up": "Subir"
                    },
                    "moveDown": {
                        "Move down": "Bajar"
                    }
                },
            }
        },
        placeholder: 'Escribe tu post aquí...',
        data: data || undefined,
        onChange: async () => {
            const data = await window.editorInstance.save();
            const currentImages = data.blocks
                .filter(block => block.type === 'image')
                .map(block => block.data.file && block.data.file.url)
                .filter(Boolean);
            // Detectar imágenes eliminadas
            const deletedImages = previousImages.filter(url => !currentImages.includes(url));
            deletedImages.forEach(url => {
                fetch('/posts/delete-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ url })
                });
            });
            previousImages = currentImages;
        }
    });
}

window.saveEditorContent = async () => {
    if (window.editorInstance) {
        const data = await window.editorInstance.save();
        document.getElementById('content-input').value = JSON.stringify(data);
    }
}
