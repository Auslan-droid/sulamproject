<?php if (!empty($donationsError)): ?>
    <div class="card page-card">
        <div class="notice error" style="margin-top: 1rem;">
            <?php echo htmlspecialchars($donationsError); ?>
        </div>
    </div>
<?php endif; ?>

<<<<<<< HEAD
<?php if (isset($donations) && count($donations) > 0): ?>
    <div class="card page-card">
        <div class="card card--elevated" style="margin-top: 2rem;">
            <h3>Donations</h3>
            <p>Click to view the detail.</p>
            <div class="card-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1rem;">
                <?php foreach ($donations as $d): ?>
                    <div class="card card--elevated donation-card"
                         data-id="<?php echo (int)$d['id']; ?>"
                         data-title="<?php echo htmlspecialchars($d['title']); ?>"
                         data-description="<?php echo htmlspecialchars($d['description']); ?>"
                         data-image="<?php echo !empty($d['image_path']) ? url('/' . htmlspecialchars($d['image_path'])) : ''; ?>"
                         style="cursor:pointer;">
                        <?php if (!empty($d['image_path'])): ?>
                            <img src="<?php echo url('/' . htmlspecialchars($d['image_path'])); ?>" alt="<?php echo htmlspecialchars($d['title']); ?>" style="width:100%; height:auto; object-fit:contain; border-radius: 6px 6px 0 0;" />
                        <?php endif; ?>
                        <div class="card-body" style="padding: 1rem;">
                            <h4 style="margin: 0 0 .5rem;">
                                <?php echo htmlspecialchars($d['title']); ?>
                            </h4>
                            <p style="color:#555;">
                                <?php echo nl2br(htmlspecialchars($d['description'])); ?>
                            </p>
=======
<div class="card page-card">
    <?php if (empty($donations)): ?>
        <div class="card card--elevated" style="margin-top: 2rem; text-align: center; padding: 3rem;">
            <i class="fa-solid fa-box-open fa-3x" style="color: #d1d5db; margin-bottom: 1rem;"></i>
            <p>No active donation campaigns at the moment.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
            <?php foreach ($donations as $donation): ?>
                <div class="card card--elevated" style="display: flex; flex-direction: column; padding: 0; overflow: hidden;">
                    <?php if (!empty($donation['image_path'])): ?>
                        <img src="<?php echo url($donation['image_path']); ?>" alt="<?php echo e($donation['title']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-hand-holding-heart fa-3x" style="color: #d1d5db;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="margin-top: 0; margin-bottom: 0.5rem; font-size: 1.25rem;"><?php echo e($donation['title']); ?></h3>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.5rem; flex: 1; line-height: 1.5;"><?php echo nl2br(e($donation['description'])); ?></p>
                        
                        <div style="margin-top: auto;">
                            <button class="btn btn-primary" style="width: 100%;">Donate Now</button>
>>>>>>> edfc9e93bacc42366bb1fe00aed19e0bcc742c0a
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
<<<<<<< HEAD
    </div>

    <!-- Modal -->
    <div id="donationModal" style="position:fixed; inset:0; background:rgba(0,0,0,.6); display:none; align-items:center; justify-content:center; padding:1rem; z-index:1000;">
        <div style="background:#fff; max-width:900px; width:100%; border-radius:8px; overflow:hidden; box-shadow:0 10px 20px rgba(0,0,0,.2);">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid #eee;">
                <h3 id="donationModalTitle" style="margin:0; font-size:1.25rem;"></h3>
                <button id="donationModalClose" aria-label="Close" style="border:none; background:transparent; font-size:1.25rem; cursor:pointer;">×</button>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0;">
                <div style="padding:1rem; border-right:1px solid #eee;">
                    <img id="donationModalImage" src="" alt="" style="width:100%; height:auto; object-fit:contain;" />
                </div>
                <div style="padding:1rem;">
                    <div id="donationModalDescription" style="color:#444; white-space:pre-wrap;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const modal = document.getElementById('donationModal');
        const mTitle = document.getElementById('donationModalTitle');
        const mImg = document.getElementById('donationModalImage');
        const mDesc = document.getElementById('donationModalDescription');
        const mClose = document.getElementById('donationModalClose');

        function openModal(data){
            mTitle.textContent = data.title || '';
            if (data.image) {
                mImg.src = data.image;
                mImg.style.display = '';
            } else {
                mImg.removeAttribute('src');
                mImg.style.display = 'none';
            }
            mDesc.textContent = data.description || '';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal(){
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.donation-card').forEach(function(card){
            card.addEventListener('click', function(){
                openModal({
                    title: card.getAttribute('data-title'),
                    description: card.getAttribute('data-description'),
                    image: card.getAttribute('data-image')
                });
            });
        });

        mClose.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e){
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') closeModal();
        });
    })();
    </script>
<?php else: ?>
    <!-- No placeholder shown when there are no donations -->
<?php endif; ?>
=======
    <?php endif; ?>
</div>
>>>>>>> edfc9e93bacc42366bb1fe00aed19e0bcc742c0a
