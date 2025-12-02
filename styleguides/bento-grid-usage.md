# Bento Grid System - Usage Guide

## Quick Start

Just add the bento grid container and cards with sizing classes:

```html
<link rel="stylesheet" href="bento-grid.css">

<div class="bento-grid">
    <!-- Your cards here -->
</div>
```

## Card Sizing Classes

### Basic Sizes (Width × Height)

| Class | Size | Best For |
|-------|------|----------|
| `.bento-1x1` | 1 column × 1 row | Small stats, icons |
| `.bento-2x1` | 2 columns × 1 row | Horizontal cards, actions |
| `.bento-1x2` | 1 column × 2 rows | Vertical lists, feeds |
| `.bento-2x2` | 2 columns × 2 rows | Hero cards, featured content |
| `.bento-3x1` | 3 columns × 1 row | Wide bars, navigation |
| `.bento-1x3` | 1 column × 3 rows | Long lists |
| `.bento-3x2` | 3 columns × 2 rows | Large featured areas |
| `.bento-4x2` | 4 columns × 2 rows | Extra wide sections |
| `.bento-full` | Full width | Headers, footers |

### Manual Control

You can also mix and match:
- **Columns**: `.col-span-1`, `.col-span-2`, `.col-span-3`, `.col-span-4`, `.col-span-full`
- **Rows**: `.row-span-1`, `.row-span-2`, `.row-span-3`, `.row-span-4`

## Card Types (Optional Presets)

### Balance/Hero Card
```html
<div class="bento-card bento-2x2 card-balance">
    <div class="card-header">
        <div class="bento-icon">💰</div>
        <div>
            <div class="bento-label">JUMLAH BAKI</div>
            <p style="margin: 0; font-size: 0.85rem; font-weight: 500;">Total Balance</p>
        </div>
    </div>
    <div class="balance-value">RM 48,350.00</div>
    <div class="balance-change">
        <span class="bento-badge bento-badge-success">↑ 12.5%</span>
        <span>dari bulan lepas</span>
    </div>
    <div class="balance-breakdown">
        <div class="breakdown-item">
            <div class="breakdown-label">Tunai</div>
            <div class="breakdown-value">RM 8,350</div>
        </div>
        <div class="breakdown-item">
            <div class="breakdown-label">Bank</div>
            <div class="breakdown-value">RM 40,000</div>
        </div>
    </div>
</div>
```

### Stat Card
```html
<div class="bento-card bento-1x1 card-stat">
    <div class="bento-flex bento-gap-sm" style="margin-bottom: 0.75rem;">
        <div class="bento-icon bento-icon-sm" style="background: #d1fae5; color: #065f46;">📥</div>
        <div class="bento-label">Terimaan</div>
    </div>
    <div class="bento-value">RM 15,200</div>
    <div style="color: #4b7660; font-size: 0.85rem;">↑ 8 resit</div>
</div>
```

### Simple Content Card
```html
<div class="bento-card bento-2x1">
    <h3 class="bento-title">⚡ Tindakan Pantas</h3>
    <p>Your custom content here...</p>
</div>
```

## Utility Classes

### Icons
- `.bento-icon` - Standard icon (48×48px)
- `.bento-icon-sm` - Small icon (40×40px)

### Badges
- `.bento-badge` + color variant:
  - `.bento-badge-success` - Green (positive)
  - `.bento-badge-danger` - Red (negative)
  - `.bento-badge-warning` - Yellow (caution)
  - `.bento-badge-info` - Blue (neutral)

### Typography
- `.bento-title` - Card heading
- `.bento-label` - Small uppercase label
- `.bento-value` - Large number display
  - `.bento-value-lg` - Extra large
  - `.bento-value-sm` - Smaller

### Layout
- `.bento-flex` - Flex container
- `.bento-flex-between` - Space between items
- `.bento-flex-center` - Center items
- `.bento-flex-col` - Column direction
- `.bento-gap`, `.bento-gap-sm`, `.bento-gap-lg` - Gap spacing

### Buttons
- `.bento-btn` - Standard button styling with hover effects

## Real Example: Building a Dashboard

```html
<div class="bento-grid">
    
    <!-- Hero Balance Card (2×2) -->
    <div class="bento-card bento-2x2 card-balance">
        <div class="card-header">
            <div class="bento-icon">💰</div>
            <div>
                <div class="bento-label">JUMLAH BAKI</div>
                <p style="margin: 0;">Total Balance</p>
            </div>
        </div>
        <div class="balance-value">RM 48,350.00</div>
        <div class="balance-breakdown">
            <div class="breakdown-item">
                <div class="breakdown-label">Tunai</div>
                <div class="breakdown-value">RM 8,350</div>
            </div>
            <div class="breakdown-item">
                <div class="breakdown-label">Bank</div>
                <div class="breakdown-value">RM 40,000</div>
            </div>
        </div>
    </div>

    <!-- Stat Cards (1×1 each) -->
    <div class="bento-card bento-1x1">
        <div class="bento-icon bento-icon-sm" style="background: #d1fae5; color: #065f46;">📥</div>
        <div class="bento-value-sm" style="color: #065f46; margin: 0.5rem 0;">RM 15,200</div>
        <div class="bento-label">Terimaan (Dec)</div>
    </div>

    <div class="bento-card bento-1x1">
        <div class="bento-icon bento-icon-sm" style="background: #fee2e2; color: #991b1b;">📤</div>
        <div class="bento-value-sm" style="color: #991b1b; margin: 0.5rem 0;">RM 9,800</div>
        <div class="bento-label">Bayaran (Dec)</div>
    </div>

    <!-- Quick Actions (2×2) -->
    <div class="bento-card bento-2x2">
        <h3 class="bento-title">⚡ Tindakan Pantas</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; height: 100%;">
            <button class="bento-btn">
                <span style="font-size: 2rem;">📝</span>
                <span>Terimaan</span>
            </button>
            <button class="bento-btn">
                <span style="font-size: 2rem;">💸</span>
                <span>Bayaran</span>
            </button>
            <button class="bento-btn">
                <span style="font-size: 2rem;">📊</span>
                <span>Laporan</span>
            </button>
            <button class="bento-btn">
                <span style="font-size: 2rem;">⚙️</span>
                <span>Tetapan</span>
            </button>
        </div>
    </div>

    <!-- Data Table (2×2) -->
    <div class="bento-card bento-2x2">
        <div class="bento-flex-between" style="margin-bottom: 1rem;">
            <h3 class="bento-title" style="margin: 0;">📖 Buku Tunai</h3>
            <button class="bento-badge bento-badge-info" style="cursor: pointer;">Lihat</button>
        </div>
        <table style="width: 100%;">
            <!-- Your table content -->
        </table>
    </div>

</div>
```

## Pro Tips

1. **Start with sizing**: Use `.bento-2x2` or `.bento-1x1` first
2. **Add card type** (optional): `.card-balance`, `.card-stat`, etc.
3. **Use utilities**: `.bento-icon`, `.bento-badge`, `.bento-value` for consistent styling
4. **Customize freely**: All utility classes are optional - add your own inline styles as needed

## Responsive Behavior

- **Desktop (>1024px)**: 4 columns
- **Tablet (768-1024px)**: 3 columns  
- **Mobile (480-768px)**: 2 columns
- **Small Mobile (<480px)**: 1 column (stacked)

Large cards automatically adjust to fit smaller grids.
