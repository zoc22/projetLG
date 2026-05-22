import { ref, onUnmounted } from 'vue'
import Chart from 'chart.js/auto'
import { useUIStore } from '@/stores/uiStore'

export function useChart() {
  const uiStore = useUIStore()
  let earningsChart = null

  const initChart = (canvasId) => {
    const canvas = document.getElementById(canvasId)
    if (!canvas) return
    
    // Rechercher une instance existante de Chart.js sur ce canvas et la détruire
    const existingChart = Chart.getChart(canvas)
    if (existingChart) {
      existingChart.destroy()
    }
    
    const ctx = canvas.getContext('2d')
    const darkMode = uiStore.darkMode
    const gridColor = darkMode ? 'rgba(255, 255, 255, 0.1)' : '#e5e7eb'
    const textColor = darkMode ? '#9ca3af' : '#6b7280'
    
    earningsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
        datasets: [{
          label: 'Gains (€)',
          data: [850, 1247, 1390, 1247],
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
          pointBackgroundColor: '#3b82f6',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: darkMode ? '#1f2937' : '#ffffff',
            titleColor: darkMode ? '#ffffff' : '#1f2937',
            bodyColor: darkMode ? '#9ca3af' : '#6b7280',
            borderColor: darkMode ? '#374151' : '#e5e7eb',
            borderWidth: 1,
            padding: 10,
            displayColors: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: gridColor, drawBorder: false },
            ticks: {
              color: textColor,
              stepSize: 500,
              callback: (value) => value + '€'
            }
          },
          x: {
            grid: { display: false },
            ticks: { color: textColor }
          }
        },
        layout: {
          padding: { top: 20, bottom: 10, left: 10, right: 10 }
        }
      }
    })
  }

  onUnmounted(() => {
    if (earningsChart) {
      earningsChart.destroy()
    }
  })

  return {
    initChart
  }
}
