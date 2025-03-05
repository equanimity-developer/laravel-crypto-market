export function formatPrice(price) {
  if (price >= 1) {
    return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  } else {
    return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 6 });
  }
}

export function formatPercentage(percentage) {
  if (!percentage) return '0.00%';
  return percentage.toFixed(2) + '%';
}

export function formatMarketCap(marketCap) {
  if (marketCap >= 1e9) {
    return (marketCap / 1e9).toFixed(2) + 'B';
  } else if (marketCap >= 1e6) {
    return (marketCap / 1e6).toFixed(2) + 'M';
  } else {
    return marketCap.toLocaleString('en-US');
  }
}

export function getPriceChangeClass(change) {
  if (change === null) return 'price-neutral';
  return change >= 0 ? 'price-positive' : 'price-negative';
} 