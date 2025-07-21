import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Separator } from "@/components/ui/separator"
import {
  Star,
  MapPin,
  Phone,
  Mail,
  Wifi,
  Car,
  Utensils,
  Waves,
  Dumbbell,
  SpadeIcon as Spa,
  Calendar,
  Users,
  ArrowLeft,
} from "lucide-react"
import Image from "next/image"
import Link from "next/link"

// This would typically come from a database or API
const getHotelById = (id: string) => {
  const hotels = {
    "1": {
      id: 1,
      name: "Grand Ocean Resort",
      location: "Miami Beach, Florida",
      rating: 4.8,
      reviews: 1247,
      images: [
        "/placeholder.svg?height=400&width=600",
        "/placeholder.svg?height=300&width=400",
        "/placeholder.svg?height=300&width=400",
        "/placeholder.svg?height=300&width=400",
        "/placeholder.svg?height=300&width=400",
        "/placeholder.svg?height=300&width=400",
      ],
      description:
        "Experience luxury at its finest at Grand Ocean Resort, where pristine beaches meet world-class hospitality. Our beachfront property offers stunning ocean views, exceptional dining, and unparalleled service that will make your stay unforgettable.",
      phone: "+1 (305) 555-0123",
      email: "info@grandoceanresort.com",
      website: "www.grandoceanresort.com",
      address: "123 Ocean Drive, Miami Beach, FL 33139",
      checkIn: "3:00 PM",
      checkOut: "11:00 AM",
      specialOffer: "Book 3 nights, get 1 free - Limited time offer!",
      amenities: [
        "Free WiFi",
        "Valet Parking",
        "Beachfront Restaurant",
        "Beach Access",
        "Fitness Center",
        "Full-Service Spa",
        "Pool Bar",
        "Concierge Service",
        "Room Service",
        "Business Center",
        "Pet Friendly",
        "Airport Shuttle",
      ],
      rooms: [
        {
          type: "Ocean View Suite",
          price: 299,
          description:
            "Spacious 650 sq ft suite with panoramic ocean views, king bed, separate living area, and private balcony",
          capacity: "2-4 guests",
          amenities: ["Ocean View", "King Bed", "Living Area", "Balcony", "Mini Bar"],
        },
        {
          type: "Deluxe Room",
          price: 199,
          description: "Comfortable 450 sq ft room with modern amenities, queen bed, and partial ocean view",
          capacity: "2 guests",
          amenities: ["Partial Ocean View", "Queen Bed", "Work Desk", "Mini Fridge"],
        },
        {
          type: "Presidential Suite",
          price: 599,
          description:
            "Ultimate luxury 1200 sq ft suite with panoramic views, master bedroom, dining area, and private terrace",
          capacity: "4-6 guests",
          amenities: ["Panoramic View", "Master Bedroom", "Dining Area", "Private Terrace", "Butler Service"],
        },
      ],
      dining: [
        {
          name: "Ocean Breeze Restaurant",
          type: "Fine Dining",
          cuisine: "Mediterranean",
          hours: "6:00 PM - 11:00 PM",
          description: "Award-winning restaurant featuring fresh seafood and Mediterranean cuisine",
        },
        {
          name: "Poolside Grill",
          type: "Casual Dining",
          cuisine: "American",
          hours: "11:00 AM - 10:00 PM",
          description: "Relaxed poolside dining with burgers, salads, and tropical drinks",
        },
      ],
      policies: [
        "Check-in: 3:00 PM | Check-out: 11:00 AM",
        "Cancellation: Free cancellation up to 24 hours before arrival",
        "Pet Policy: Pets welcome with $50 per night fee",
        "Smoking: Non-smoking property",
        "Age Requirement: Guests must be 21+ to check in",
      ],
    },
  }
  return hotels[id as keyof typeof hotels]
}

export default function HotelDetailPage({ params }: { params: { id: string } }) {
  const hotel = getHotelById(params.id)

  if (!hotel) {
    return <div>Hotel not found</div>
  }

  const amenityIcons = {
    "Free WiFi": Wifi,
    "Valet Parking": Car,
    "Beachfront Restaurant": Utensils,
    "Beach Access": Waves,
    "Fitness Center": Dumbbell,
    "Full-Service Spa": Spa,
    "Pool Bar": Utensils,
    "Concierge Service": Phone,
    "Room Service": Utensils,
    "Business Center": Utensils,
    "Pet Friendly": Phone,
    "Airport Shuttle": Car,
  }

  return (
    <div className="min-h-screen bg-background">
      {/* Header */}
      <header className="border-b bg-white sticky top-0 z-50">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <Link href="/" className="flex items-center space-x-2">
              <ArrowLeft className="w-5 h-5" />
              <span className="text-lg font-semibold">Back to Hotels</span>
            </Link>
            <Button>Book Now</Button>
          </div>
        </div>
      </header>

      {/* Hero Section with Image Gallery */}
      <section className="py-8">
        <div className="container mx-auto px-4">
          <div className="grid md:grid-cols-4 gap-4 mb-8">
            <div className="md:col-span-2 md:row-span-2">
              <Image
                src={hotel.images[0] || "/placeholder.svg"}
                alt={hotel.name}
                width={600}
                height={400}
                className="w-full h-full object-cover rounded-lg"
              />
            </div>
            {hotel.images.slice(1, 5).map((image, index) => (
              <div key={index} className="relative">
                <Image
                  src={image || "/placeholder.svg"}
                  alt={`${hotel.name} view ${index + 2}`}
                  width={300}
                  height={200}
                  className="w-full h-48 object-cover rounded-lg"
                />
                {index === 3 && (
                  <div className="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                    <Button variant="secondary">View All Photos</Button>
                  </div>
                )}
              </div>
            ))}
          </div>

          <div className="grid lg:grid-cols-3 gap-8">
            {/* Main Content */}
            <div className="lg:col-span-2 space-y-8">
              {/* Hotel Header */}
              <div>
                <div className="flex items-start justify-between mb-4">
                  <div>
                    <h1 className="text-3xl font-bold mb-2">{hotel.name}</h1>
                    <div className="flex items-center text-muted-foreground mb-2">
                      <MapPin className="w-4 h-4 mr-1" />
                      {hotel.location}
                    </div>
                    <div className="flex items-center space-x-2">
                      <div className="flex items-center space-x-1">
                        <Star className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                        <span className="font-semibold">{hotel.rating}</span>
                      </div>
                      <span className="text-muted-foreground">({hotel.reviews} reviews)</span>
                    </div>
                  </div>
                  {hotel.specialOffer && <Badge className="bg-red-500 hover:bg-red-600">Special Offer</Badge>}
                </div>
                <p className="text-muted-foreground leading-relaxed">{hotel.description}</p>
              </div>

              <Separator />

              {/* Special Offer */}
              {hotel.specialOffer && (
                <div className="bg-red-50 border border-red-200 rounded-lg p-4">
                  <h3 className="font-semibold text-red-800 mb-2">Special Offer</h3>
                  <p className="text-red-700">{hotel.specialOffer}</p>
                </div>
              )}

              {/* Room Types */}
              <div>
                <h2 className="text-2xl font-bold mb-6">Room Types & Pricing</h2>
                <div className="space-y-4">
                  {hotel.rooms.map((room, index) => (
                    <Card key={index}>
                      <CardContent className="p-6">
                        <div className="flex justify-between items-start mb-4">
                          <div className="flex-1">
                            <h3 className="text-xl font-semibold mb-2">{room.type}</h3>
                            <p className="text-muted-foreground mb-3">{room.description}</p>
                            <div className="flex items-center space-x-4 text-sm text-muted-foreground mb-3">
                              <div className="flex items-center">
                                <Users className="w-4 h-4 mr-1" />
                                {room.capacity}
                              </div>
                            </div>
                            <div className="flex flex-wrap gap-2">
                              {room.amenities.map((amenity, i) => (
                                <Badge key={i} variant="secondary" className="text-xs">
                                  {amenity}
                                </Badge>
                              ))}
                            </div>
                          </div>
                          <div className="text-right ml-6">
                            <div className="text-2xl font-bold">${room.price}</div>
                            <div className="text-sm text-muted-foreground">per night</div>
                            <Button className="mt-3">Select Room</Button>
                          </div>
                        </div>
                      </CardContent>
                    </Card>
                  ))}
                </div>
              </div>

              <Separator />

              {/* Amenities */}
              <div>
                <h2 className="text-2xl font-bold mb-6">Hotel Amenities</h2>
                <div className="grid md:grid-cols-2 gap-4">
                  {hotel.amenities.map((amenity, index) => {
                    const IconComponent = amenityIcons[amenity as keyof typeof amenityIcons] || Wifi
                    return (
                      <div key={index} className="flex items-center space-x-3 p-3 bg-muted rounded-lg">
                        <IconComponent className="w-5 h-5 text-primary" />
                        <span>{amenity}</span>
                      </div>
                    )
                  })}
                </div>
              </div>

              <Separator />

              {/* Dining */}
              <div>
                <h2 className="text-2xl font-bold mb-6">Dining Options</h2>
                <div className="space-y-4">
                  {hotel.dining.map((restaurant, index) => (
                    <Card key={index}>
                      <CardContent className="p-6">
                        <div className="flex justify-between items-start">
                          <div>
                            <h3 className="text-lg font-semibold mb-1">{restaurant.name}</h3>
                            <div className="flex items-center space-x-4 text-sm text-muted-foreground mb-2">
                              <span>{restaurant.type}</span>
                              <span>•</span>
                              <span>{restaurant.cuisine}</span>
                              <span>•</span>
                              <span>{restaurant.hours}</span>
                            </div>
                            <p className="text-muted-foreground">{restaurant.description}</p>
                          </div>
                        </div>
                      </CardContent>
                    </Card>
                  ))}
                </div>
              </div>

              <Separator />

              {/* Policies */}
              <div>
                <h2 className="text-2xl font-bold mb-6">Hotel Policies</h2>
                <div className="space-y-3">
                  {hotel.policies.map((policy, index) => (
                    <div key={index} className="flex items-start space-x-3">
                      <div className="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
                      <p className="text-muted-foreground">{policy}</p>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Booking Sidebar */}
            <div className="lg:col-span-1">
              <Card className="sticky top-24">
                <CardHeader>
                  <CardTitle>Book Your Stay</CardTitle>
                </CardHeader>
                <CardContent className="space-y-6">
                  <div>
                    <h4 className="font-semibold mb-3">Contact Information</h4>
                    <div className="space-y-3">
                      <div className="flex items-center space-x-3">
                        <Phone className="w-4 h-4 text-primary" />
                        <span className="text-sm">{hotel.phone}</span>
                      </div>
                      <div className="flex items-center space-x-3">
                        <Mail className="w-4 h-4 text-primary" />
                        <span className="text-sm">{hotel.email}</span>
                      </div>
                      <div className="flex items-center space-x-3">
                        <MapPin className="w-4 h-4 text-primary" />
                        <span className="text-sm">{hotel.address}</span>
                      </div>
                    </div>
                  </div>

                  <Separator />

                  <div>
                    <h4 className="font-semibold mb-3">Check-in Information</h4>
                    <div className="space-y-2 text-sm text-muted-foreground">
                      <div className="flex justify-between">
                        <span>Check-in:</span>
                        <span>{hotel.checkIn}</span>
                      </div>
                      <div className="flex justify-between">
                        <span>Check-out:</span>
                        <span>{hotel.checkOut}</span>
                      </div>
                    </div>
                  </div>

                  <Separator />

                  <div className="space-y-3">
                    <Button className="w-full" size="lg">
                      <Calendar className="w-4 h-4 mr-2" />
                      Book Now
                    </Button>
                    <Button variant="outline" className="w-full bg-transparent">
                      Call Hotel
                    </Button>
                    <Button variant="outline" className="w-full bg-transparent">
                      Get Directions
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}
