document.addEventListener('DOMContentLoaded', () => {
  window.openSidebar = () => {
    document.getElementById("mySidebar").style.width = "250px";
  };

  window.closeSidebar = () => {
    document.getElementById("mySidebar").style.width = "0";
  };

  
const ctx1 = document.getElementById('linhasOperando').getContext('2d');
const linhasOperandoChart = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['A', 'B', 'C', 'D'],
        datasets: [{
            label: 'Linhas Operando',
            data: [4, 8, 5, 7],
            backgroundColor: ['#FFD700', '#FFA500', '#FF8C00', '#FF4500'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                enabled: true
            }
        }
    }
});


const ctx2 = document.getElementById('operacao').getContext('2d');
const operacaoChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Operando', 'Não Operando'],
        datasets: [{
            data: [35, 12],
            backgroundColor: ['#FFA500', '#FFD700']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                enabled: true
            }
        }
    }
});


const ctx3 = document.getElementById('atrasos').getContext('2d');
const atrasosChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ['A', 'B', 'C', 'D'],
        datasets: [{
            label: 'Atrasos',
            data: [2, 4, 1, 2],
            backgroundColor: ['#FFA500', '#FF8C00', '#FF4500', '#FFD700']
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            tooltip: {
                enabled: true
            }
        }
    }
});
})