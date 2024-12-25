<?php
namespace Almhdy\Simy\Core;

class Pagination
{
  private int $totalItems; // Total number of items
  private int $itemsPerPage; // Items per page
  private int $currentPage; // Current page number
  private int $totalPages; // Total pages

  public function __construct(
    int $totalItems,
    int $itemsPerPage = 10,
    int $currentPage = 1
  ) {
    // Initialize properties
    $this->totalItems = $totalItems;
    $this->itemsPerPage = $itemsPerPage;
    $this->currentPage = $currentPage;

    // Calculate total pages
    $this->totalPages = (int) ceil($this->totalItems / $this->itemsPerPage);
  }

  public function getCurrentPage(): int
  {
    return $this->currentPage;
  }

  public function getTotalPages(): int
  {
    return $this->totalPages;
  }

  public function hasNext(): bool
  {
    return $this->currentPage < $this->totalPages;
  }

  public function hasPrevious(): bool
  {
    return $this->currentPage > 1;
  }

  public function getNextPage(): ?int
  {
    return $this->hasNext() ? $this->currentPage + 1 : null;
  }

  public function getPreviousPage(): ?int
  {
    return $this->hasPrevious() ? $this->currentPage - 1 : null;
  }

  public function getStartItem(): int
  {
    return ($this->currentPage - 1) * $this->itemsPerPage + 1;
  }

  public function getEndItem(): int
  {
    return min($this->totalItems, $this->currentPage * $this->itemsPerPage);
  }

  public function createLinks(string $baseUrl): string
{
    $links = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

    if ($this->hasPrevious()) {
        $links .= '<li class="page-item">
            <a class="page-link" href="' . $baseUrl . '?page=' . $this->getPreviousPage() . '" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>';
    }

    for ($i = 1; $i <= $this->totalPages; $i++) {
        if ($i == $this->currentPage) {
            $links .= '<li class="page-item active" aria-current="page">
                <span class="page-link">' . $i . '</span>
            </li>';
        } else {
            $links .= '<li class="page-item">
                <a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a>
            </li>';
        }
    }

    if ($this->hasNext()) {
        $links .= '<li class="page-item">
            <a class="page-link" href="' . $baseUrl . '?page=' . $this->getNextPage() . '" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>';
    }

    $links .= '</ul></nav>';

    return $links;
}
}
