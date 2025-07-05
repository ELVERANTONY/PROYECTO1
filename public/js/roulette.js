document.addEventListener('DOMContentLoaded', function() {
    let data = window.rouletteData;
    if (!data || !data.estudiantes) {
        console.error('Error: No se encontraron los datos de la ruleta (rouletteData).');
        document.getElementById('spin-button').disabled = true;
        return;
    }

    // --- ELEMENTOS DEL DOM ---
    const wheelContainer = document.getElementById('wheel-container');
    const spinButton = document.getElementById('spin-button');
    const studentModalElement = document.getElementById('winnerModal');
    const studentModal = new bootstrap.Modal(studentModalElement);
    const studentInfoContainer = document.getElementById('winner-info');
    const questionContainer = document.getElementById('winner-question');
    const nextQuestionBtn = document.getElementById('ask-another-question-btn');
    const backToRouletteBtn = document.getElementById('spin-again-btn');

    // --- ESTADO DE LA RULETA ---
    let laRueda = null;
    let wheelSpinning = false;

    // Función para actualizar estudiantes en la ruleta y en la lista
    async function actualizarEstudiantes() {
        try {
            const response = await fetch(`/clases/${data.claseId}/participantes`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Error al obtener estudiantes');
            const estudiantesActualizados = await response.json();

            if (estudiantesActualizados.length === 0) {
                wheelContainer.innerHTML = '<p class="text-center text-warning">No hay estudiantes en la clase para girar la ruleta.</p>';
                spinButton.disabled = true;
                return;
            }

            // Actualizar datos de la ruleta
            data.estudiantes = estudiantesActualizados.map(user => {
                const hash = user.textFillStyle ? user.textFillStyle : '000000';
                const r = parseInt(hash.substring(0, 2), 16);
                const g = parseInt(hash.substring(2, 4), 16);
                const b = parseInt(hash.substring(4, 6), 16);
                const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
                return {
                    id: user.id,
                    text: user.name,
                    fillStyle: user.fillStyle || '#'+hash,
                    textFillStyle: luminance > 0.5 ? '#000000' : '#FFFFFF',
                    skin_url: user.skin_equipada ? user.skin_equipada.imagen_url : '/images/default-skin.png'
                };
            });

            // Reconstruir la ruleta con los nuevos estudiantes
            if (laRueda) {
                laRueda.segments = data.estudiantes;
                laRueda.numSegments = data.estudiantes.length;
                laRueda.draw();
                spinButton.disabled = false;
            }
        } catch (error) {
            console.error('Error al actualizar estudiantes:', error);
        }
    }

    // Inicializar la ruleta
    function inicializarRuleta() {
        if (!data.estudiantes.length) {
            wheelContainer.innerHTML = '<p class="text-center text-warning">No hay estudiantes en la clase para girar la ruleta.</p>';
            spinButton.disabled = true;
            return;
        }

        laRueda = new Roulette(wheelContainer, data.estudiantes);
        spinButton.disabled = false;
    }

    // --- FUNCIONES AUXILIARES ---
    function drawTriangle() {
        let ctx = laRueda.ctx;
        ctx.strokeStyle = 'white';
        ctx.fillStyle = 'white';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(220, 0);
        ctx.lineTo(230, 0);
        ctx.lineTo(225, 15);
        ctx.lineTo(220, 0);
        ctx.stroke();
        ctx.fill();
    }

    window.mostrarGanador = function() {
        studentModal.show();
        wheelSpinning = false;
    }

    function popularModal(estudiante, pregunta) {
        studentInfoContainer.innerHTML = `
            <img src="${estudiante.skin_equipada ? '/storage/' + estudiante.skin_equipada.imagen_url : '/images/default-skin.png'}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--secondary);">
            <h4 class="font-weight-bold" style="color: var(--secondary);">${estudiante.name}</h4>
        `;

        let alternativasHtml = '<ul class="list-group text-start">';
        pregunta.alternativas.forEach(alt => {
            alternativasHtml += `<li class="list-group-item" style="background-color: var(--dark-deep); color: var(--light); border-color: rgba(15, 255, 192, 0.2);">${alt.texto_alternativa}</li>`;
        });
        alternativasHtml += '</ul>';

        questionContainer.innerHTML = `
            <h5 class="font-weight-bold" style="color: var(--primary);">Pregunta Asignada:</h5>
            <p class="lead">${pregunta.texto_pregunta}</p>
            ${alternativasHtml}
        `;
    }

    // --- EVENT LISTENERS ---
    spinButton.addEventListener('click', function() {
        if (wheelSpinning) return;

        wheelSpinning = true;
        spinButton.disabled = true;
        spinButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(data.llamarEstudianteUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': data.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Error en la respuesta del servidor.');
            return res.json();
        })
        .then(response => {
            if (response.error) throw new Error(response.error);

            const estudianteGanador = response.estudiante;
            const pregunta = response.pregunta;

            popularModal(estudianteGanador, pregunta);

            laRueda.spin(estudianteGanador);
        })
        .catch(error => {
            console.error('Error al girar la ruleta:', error);
            alert('Hubo un problema al seleccionar un estudiante. Por favor, intenta de nuevo.');
            wheelSpinning = false;
            spinButton.disabled = false;
            spinButton.innerHTML = 'GIRAR';
        });
    });

    nextQuestionBtn.addEventListener('click', function() {
        this.disabled = true;
        fetch(data.nuevaPreguntaUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': data.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(response => {
            if (response.error) throw new Error(response.error);
            
            let pregunta = response.pregunta;
            let alternativasHtml = '<ul class="list-group text-start">';
            pregunta.alternativas.forEach(alt => {
                alternativasHtml += `<li class="list-group-item" style="background-color: var(--dark-deep); color: var(--light); border-color: rgba(15, 255, 192, 0.2);">${alt.texto_alternativa}</li>`;
            });
            alternativasHtml += '</ul>';

            questionContainer.innerHTML = `
                <h5 class="font-weight-bold" style="color: var(--primary);">Pregunta Asignada:</h5>
                <p class="lead">${pregunta.texto_pregunta}</p>
                ${alternativasHtml}
            `;
        })
        .catch(error => {
            console.error('Error al obtener nueva pregunta:', error);
            alert('No se pudo cargar una nueva pregunta.');
        })
        .finally(() => {
            this.disabled = false;
        });
    });

    backToRouletteBtn.addEventListener('click', function() {
        studentModal.hide();
    });

    studentModalElement.addEventListener('hidden.bs.modal', function () {
        laRueda.rotation = 0;
        laRueda.draw();
        drawTriangle();

        fetch(data.resetEstadoUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': data.csrfToken }
        }).catch(err => console.error('Error al resetear estado:', err));
        
        spinButton.disabled = false;
        spinButton.innerHTML = 'GIRAR';
        wheelSpinning = false;
    });

    // Actualizar estudiantes cada 10 segundos para reflejar nuevos participantes
    setInterval(actualizarEstudiantes, 10000);

    // Inicializar ruleta al cargar la página
    inicializarRuleta();
});

class Roulette {
    constructor(canvas, options = []) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.options = [];
        this.setOptions(options); // Use setter to initialize correctly
        this.startAngle = 0;
        this.spinTimeout = null;
        this.spinAngleStart = 10;
        this.spinTime = 0;
        this.spinTimeTotal = 0;
        this.ctx.font = 'bold 16px Arial';
        this.winnerCallback = null;
    }

    setOptions(newOptions) {
        this.options = newOptions || [];
        // The arc calculation depends on the number of options.
        this.arc = this.options.length > 0 ? (2 * Math.PI) / this.options.length : 0;
    }

    byte2Hex(n) {
        const nybHexString = "0123456789ABCDEF";
        return String(nybHexString.substr((n >> 4) & 0x0F, 1)) + nybHexString.substr(n & 0x0F, 1);
    }

    RGB2Color(r, g, b) {
        return '#' + this.byte2Hex(r) + this.byte2Hex(g) + this.byte2Hex(b);
    }

    getColor(item, maxitem) {
        const phase = 0;
        const center = 128;
        const width = 127;
        const frequency = Math.PI * 2 / maxitem;
        
        const red = Math.sin(frequency * item + 2 + phase) * width + center;
        const green = Math.sin(frequency * item + 0 + phase) * width + center;
        const blue = Math.sin(frequency * item + 4 + phase) * width + center;
        
        return this.RGB2Color(red, green, blue);
    }

    draw() {
        this.clear();
        const centerX = this.canvas.width / 2;
        const centerY = this.canvas.height / 2;

        if (this.options.length === 0) {
            this.ctx.save();
            this.ctx.fillStyle = "black";
            this.ctx.textAlign = 'center';
            this.ctx.textBaseline = 'middle';
            this.ctx.font = '20px Arial';
            this.ctx.fillText("Esperando estudiantes...", centerX, centerY);
            this.ctx.restore();
            return;
        }

        const outsideRadius = 200;
        const textRadius = 160;
        const insideRadius = 50;

        this.ctx.strokeStyle = "black";
        this.ctx.lineWidth = 2;
        this.ctx.font = 'bold 16px Arial';

        for (let i = 0; i < this.options.length; i++) {
            const angle = this.startAngle + i * this.arc;
            this.ctx.fillStyle = this.getColor(i, this.options.length);

            this.ctx.beginPath();
            this.ctx.arc(centerX, centerY, outsideRadius, angle, angle + this.arc, false);
            this.ctx.arc(centerX, centerY, insideRadius, angle + this.arc, angle, true);
            this.ctx.stroke();
            this.ctx.fill();

            this.ctx.save();
            this.ctx.fillStyle = "white";
            this.ctx.translate(centerX + Math.cos(angle + this.arc / 2) * textRadius,
                               centerY + Math.sin(angle + this.arc / 2) * textRadius);
            this.ctx.rotate(angle + this.arc / 2 + Math.PI / 2);
            const text = this.options[i];
            this.ctx.fillText(text, -this.ctx.measureText(text).width / 2, 0);
            this.ctx.restore();
        }
    }
    
    spin(winner, callback) {
        this.winnerCallback = callback;
        const winnerIndex = this.options.indexOf(winner);
        if (winnerIndex === -1) {
            console.error('Winner not found in options');
            if(this.winnerCallback) this.winnerCallback();
            return;
        }

        // Calculate final angle
        const winnerAngle = winnerIndex * this.arc;
        const randomOffset = (Math.random() * 0.8 + 0.1) * this.arc; // land somewhere in the slice
        const finalAngle = (2 * Math.PI) - winnerAngle - randomOffset;

        // Randomize spin time and revolutions
        this.spinTime = 0;
        this.spinTimeTotal = (Math.random() * 3 + 4) * 1000; // 4-7 seconds spin
        const totalRevolutions = 5; // Fixed number of revolutions
        this.spinAngleStart = totalRevolutions * 2 * Math.PI;

        // Adjust start angle to point to the final position after animation
        this.startAngle = this.startAngle % (2 * Math.PI); // Normalize start angle
        const targetAngle = finalAngle + this.spinAngleStart;
        
        this.rotate(targetAngle);
    }

    rotate(targetAngle) {
        this.spinTime += 30; // Corresponds to the interval time
        if (this.spinTime >= this.spinTimeTotal) {
            // Stop the rotation
            if (this.winnerCallback) {
                this.winnerCallback();
            }
            return;
        }

        const spinAngle = this.easeOut(this.spinTime, 0, targetAngle, this.spinTimeTotal);
        this.startAngle = spinAngle;

        this.draw();
        
        this.spinTimeout = setTimeout(() => this.rotate(targetAngle), 30);
    }
    
    easeOut(t, b, c, d) {
        const ts = (t /= d) * t;
        const tc = ts * t;
        return b + c * (tc + -3 * ts + 3 * t);
    }

    clear() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }
}
