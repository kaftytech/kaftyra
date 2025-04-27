<?php

namespace App\Livewire\Filter;

use Livewire\Component;

class FilterComponent extends Component
{
    public $showFilters = false;
    public $search = '';
    public $activeFilters = [];
    public $filterConfig = [];
    public $entityType = '';
    
    public function mount($entityType, $filterConfig = null)
    {
        $this->entityType = $entityType;
        
        // Set default configurations based on entity type if not provided
        if (!$filterConfig) {
            $this->filterConfig = $this->getDefaultFilterConfig($entityType);
        } else {
            $this->filterConfig = $filterConfig;
        }
        
        // Initialize active filters with empty values
        foreach ($this->filterConfig as $key => $config) {
            if ($config['type'] === 'range') {
                $this->activeFilters[$key] = [
                    'min' => '',
                    'max' => ''
                ];
            } elseif ($config['type'] === 'multi-select') {
                $this->activeFilters[$key] = [];
            } else {
                $this->activeFilters[$key] = '';
            }
        }
    }
    
    protected function getDefaultFilterConfig($entityType)
    {
        switch ($entityType) {
            case 'leads':
                return [
                    'status' => [
                        'type' => 'multi-select',
                        'label' => 'Status',
                        'options' => [
                            'new' => 'New', 
                            'contacted' => 'Contacted',
                            'qualified' => 'Qualified', 
                            'converted' => 'Converted'
                        ]
                    ],
                    'source' => [
                        'type' => 'select',
                        'label' => 'Source',
                        'options' => [
                            'website' => 'Website',
                            'referral' => 'Referral',
                            'social' => 'Social Media'
                        ]
                    ],
                    'created_date' => [
                        'type' => 'date-range',
                        'label' => 'Created Date'
                    ]
                ];
                
            case 'customers':
                return [
                    'customer_type' => [
                        'type' => 'select',
                        'label' => 'Customer Type',
                        'options' => [
                            'individual' => 'Individual',
                            'business' => 'Business'
                        ]
                    ],
                    'status' => [
                        'type' => 'multi-select',
                        'label' => 'Status',
                        'options' => [
                            'active' => 'Active',
                            'inactive' => 'Inactive'
                        ]
                    ],
                    'registration_date' => [
                        'type' => 'date-range',
                        'label' => 'Registration Date'
                    ]
                ];
                
            case 'invoices':
                return [
                    'status' => [
                        'type' => 'multi-select',
                        'label' => 'Status',
                        'options' => [
                            'draft' => 'Draft',
                            'sent' => 'Sent',
                            'paid' => 'Paid',
                            'overdue' => 'Overdue'
                        ]
                    ],
                    'invoice_date' => [
                        'type' => 'date-range',
                        'label' => 'Invoice Date'
                    ],
                    'amount' => [
                        'type' => 'range',
                        'label' => 'Amount'
                    ]
                ];
                
            case 'products':
                return [
                    'category' => [
                        'type' => 'select',
                        'label' => 'Category',
                        'options' => [
                            'electronics' => 'Electronics',
                            'furniture' => 'Furniture',
                            'clothing' => 'Clothing',
                            'software' => 'Software',
                            'services' => 'Services'
                        ]
                    ],
                    'status' => [
                        'type' => 'multi-select',
                        'label' => 'Status',
                        'options' => [
                            'active' => 'Active',
                            'pending' => 'Pending',
                            'inactive' => 'Inactive',
                            'archived' => 'Archived'
                        ]
                    ],
                    'price' => [
                        'type' => 'range',
                        'label' => 'Price'
                    ]
                ];
                
            default:
                return [];
        }
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetFilters()
    {
        // Reset search
        $this->search = '';
        
        // Reset active filters
        foreach ($this->filterConfig as $key => $config) {
            if ($config['type'] === 'range' || $config['type'] === 'date-range') {
                $this->activeFilters[$key] = [
                    'min' => '',
                    'max' => ''
                ];
            } elseif ($config['type'] === 'multi-select') {
                $this->activeFilters[$key] = [];
            } else {
                $this->activeFilters[$key] = '';
            }
        }
        
        $this->dispatch('filtersReset');
    }

    public function applyFilters()
    {
        $filters = [
            'search' => $this->search,
            'filters' => $this->activeFilters,
            'entityType' => $this->entityType
        ];
        
        $this->dispatch('filtersApplied', $filters);
        $this->showFilters = false;
    }

    public function hasActiveFilters()
    {
        if (!empty($this->search)) {
            return true;
        }
        
        foreach ($this->activeFilters as $key => $value) {
            if (is_array($value)) {
                if (!empty(array_filter($value))) {
                    return true;
                }
            } elseif (!empty($value)) {
                return true;
            }
        }
        
        return false;
    }

    public function render()
    {
        return view('livewire.filter.filter-component');
    }
}
