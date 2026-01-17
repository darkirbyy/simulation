import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto';

// Controller that draw a chart.js for the products
export default class extends Controller {
  static values = { title: String, labels: Array, datasets: Array };

  connect() {
    console.log(this.dataValue);

    new Chart(this.element, {
      type: 'line',
      data: {
        labels: this.labelsValue,
        datasets: this.datasetsValue,
      },
      options: {
        transitions: false,
        plugins: {
          title: {
            display: true,
            text: this.titleValue,
            font: {
              size: 16,
            },
          },
        },
      },
    });
  }
}
