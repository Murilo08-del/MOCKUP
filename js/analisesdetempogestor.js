document.addEventListener('DOMContentLoaded', () => {
  window.openSidebar = () => {
    document.getElementById("mySidebar").style.width = "250px";
  };

  window.closeSidebar = () => {
    document.getElementById("mySidebar").style.width = "0";
  };

  const canvas = document.getElementById('graficoRosca');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Linhas operando', 'Operação', 'Atrasos'],
        datasets: [{
          data: [80, 10, 4, 6],
          backgroundColor: ['#a6842a', 'yellow', 'orange'],
                    borderWidth: 0
                }]
      },
      options: {
        cutout: '70%',
        plugins: {
          legend: {
            position: 'right',
            labels: {
              usePointStyle: true,
              pointStyle: 'circle',
              color: 'black'
            }
          }
        }
      }
    });
  }

  const alertas = [
    { mensagem: "Trem 001 - Linha Amarela apresentando desgaste nas rodas.", tipo: "info" },
    { mensagem: "Trem 004 - Linha Azul com luzes fracas dentro do vagão.", tipo: "warning" },
    { mensagem: "Todos os trens operando normalmente", tipo: "success" },
    { mensagem: "Trem 003 - Linha Verde manutenção solicitada", tipo: "info" }
  ];

  const alertMessage = document.getElementById('alert-message');
  const alertTime = document.getElementById('alert-time');

  function mostrarAlerta() {
    if (!alertMessage || !alertTime) return;

    const alerta = alertas[Math.floor(Math.random() * alertas.length)];
    alertMessage.textContent = alerta.mensagem;
    alertTime.textContent = new Date().toLocaleTimeString();

    const card = document.querySelector('.alertas');
    if (card) {
      card.style.borderLeftColor =
        alerta.tipo === 'warning' ? '#e74c3c' :
          alerta.tipo === 'success' ? '#2ecc71' : '#3498db';
    }
  }

  if (alertMessage && alertTime) {
    mostrarAlerta();
    setInterval(mostrarAlerta, 10000);
  }

  window.enviar = () => {
    const linha = document.getElementById('linha')?.value;
    const trem = document.getElementById('trem')?.value;
    const manutencao = document.getElementById('manutencao')?.value;
    const prioridade = document.getElementById('prioridade')?.value;

    console.log({ linha, trem, manutencao, prioridade });
  };
});

