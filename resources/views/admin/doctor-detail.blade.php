<!-- Simple Modern Doctor Detail -->
<div class="doctor-detail-modern">
    <!-- Header Section -->
    <div class="doctor-header">
        <div class="doctor-avatar">
            <i class="fas fa-user-md"></i>
        </div>
        <div class="doctor-info">
            <h3 class="doctor-name">{{ $doctor->name }}</h3>
            <div class="doctor-specialty">{{ $doctor->specialty }}</div>
            <div class="doctor-status {{ $doctor->is_active ? 'active' : 'inactive' }}">
                {{ $doctor->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </div>
        </div>
    </div>

    <!-- Information Grid -->
    <div class="info-grid">
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $doctor->email }}</div>
            </div>
        </div>

        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Telepon</div>
                <div class="info-value">{{ $doctor->phone }}</div>
            </div>
        </div>

        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Jadwal Praktik</div>
                <div class="info-value">
                    @if(is_array($doctor->schedule))
                        @foreach($doctor->schedule as $day => $times)
                            {{ ucfirst($day) }}: {{ $times['start'] ?? '' }}-{{ $times['end'] ?? '' }}<br>
                        @endforeach
                    @else
                        {{ $doctor->schedule }}
                    @endif
                </div>
            </div>
        </div>

        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Dibuat</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($doctor->created_at)->format('d M Y') }}</div>
            </div>
        </div>
    </div>
</div>

<style>
.doctor-detail-modern {
    padding: 0;
}

.doctor-header {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.doctor-avatar {
    width: 60px;
    height: 60px;
    background: #f3f4f6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.doctor-avatar i {
    font-size: 1.5rem;
    color: #6b7280;
}

.doctor-info {
    flex: 1;
}

.doctor-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.doctor-specialty {
    font-size: 0.875rem;
    color: #6b7280;
    background: #f9fafb;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 0.5rem;
    word-break: break-word;
    max-width: 100%;
}

.doctor-status {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
}

.doctor-status.active {
    background: #dcfce7;
    color: #166534;
}

.doctor-status.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: #f3f4f6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.info-icon i {
    font-size: 1rem;
    color: #6b7280;
}

.info-content {
    flex: 1;
    min-width: 0;
}

.info-label {
    font-size: 0.75rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
    word-break: break-word;
}

@media (max-width: 768px) {
    .doctor-header {
        flex-direction: column;
        text-align: center;
    }
    
    .doctor-avatar {
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
