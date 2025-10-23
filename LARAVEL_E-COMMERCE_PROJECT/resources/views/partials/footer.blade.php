{{-- resources/views/partials/footer.blade.php (FINAL REVISION) --}}
<footer class="bg-gray-800 mt-12" style="background-color: #333333;">
    {{-- FINAL FIX: max-w-screen-2xl at px-10/lg:px-16 --}}
    <div class="max-w-screen-2xl mx-auto px-10 lg:px-16 py-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">



            <div>
                {{-- Shop Links --}}
                <h4 class="text-base font-semibold mb-3 text-gray-200">Shop</h4>
                <ul class="space-y-2 text-xs text-gray-400" style="color: #CCCCCC;">
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">All Products</a></li>
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">New Arrivals</a></li>
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">Best Sellers</a></li>
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">Gift Cards</a></li>
                </ul>
            </div>

            <div>
                {{-- Company Links --}}
                <h4 class="text-base font-semibold mb-3 text-gray-200">Company</h4>
                <ul class="space-y-2 text-xs text-gray-400" style="color: #CCCCCC;">
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">Our Story</a></li>
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">Careers</a></li>
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">Press</a></li>
                </ul>
            </div>

            <div>
                {{-- Help Links --}}
                <h4 class="text-base font-semibold mb-3 text-gray-200">Help</h4>
                <ul class="space-y-2 text-xs text-gray-400" style="color: #CCCCCC;">
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">FAQs</a></li>
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">Shipping & Returns</a></li>
                    <li><a href="#" class="hover:underline" style="color: #CCCCCC; transition: color 0.2s;" onmouseover="this.style.color='#C5B099'" onmouseout="this.style.color='#CCCCCC'">Contact Us</a></li>
                </ul>
            </div>

        </div>

        {{-- Footer line at copyright --}}
        <div class="mt-8 pt-4 border-t" style="border-color: #555555; opacity: 0.8;">
            <p class="text-center text-xs" style="color: #AAAAAA;">
                &copy; 2025 LUMIÈRE Goods. All rights reserved.
            </p>
        </div>
    </div>
</footer>