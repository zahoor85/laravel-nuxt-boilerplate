<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <Navbar />
        <main class="flex-grow p-4">
           
            <slot />
            
        </main>
        <Footer />
    </div>
    
</template>