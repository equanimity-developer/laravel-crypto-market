export function filterAndSortCryptos(cryptos, filters, sortField, sortDirection) {
  let result = [...cryptos];

  if (filters.search) {
    const searchTerm = filters.search.toLowerCase();
    result = result.filter(crypto =>
      crypto.name.toLowerCase().includes(searchTerm) ||
      crypto.symbol.toLowerCase().includes(searchTerm)
    );
  }

  if (filters.min_price) {
    const minPrice = parseFloat(filters.min_price);
    if (!isNaN(minPrice)) {
      result = result.filter(crypto => crypto.current_price >= minPrice);
    }
  }

  if (filters.max_price) {
    const maxPrice = parseFloat(filters.max_price);
    if (!isNaN(maxPrice)) {
      result = result.filter(crypto => crypto.current_price <= maxPrice);
    }
  }

  return result.sort((a, b) => {
    const aValue = a[sortField];
    const bValue = b[sortField];

    if (aValue == null && bValue == null) return 0;
    if (aValue == null) return 1;
    if (bValue == null) return -1;

    const modifier = sortDirection === 'asc' ? 1 : -1;

    if (typeof aValue === 'string') {
      return aValue.localeCompare(bValue) * modifier;
    } else {
      return (aValue - bValue) * modifier;
    }
  });
}

export function getPaginatedData(data, page, itemsPerPage) {
  const startIndex = (page - 1) * itemsPerPage;
  return data.slice(startIndex, startIndex + itemsPerPage);
}
