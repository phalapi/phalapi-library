//
//  EASYCryptclass.m
//  EASY vision 1.0
//
//  Created by wangHuiMing on 15/10/16.
//  Copyright © 2015年 Xuyang Gordon Wang. All rights reserved.
//

#import "EASYCryptclass.h"
#import "MyDES.h"
@implementation EASYCryptclass
+(instancetype)initManager{
    return [[self alloc] init];
}

+ (NSString *)useDESEncryptString:(NSString *)str SetKey:(NSString *) sKey{
    return [[MyDES shareManager] encryptUseDES:str key: sKey];
}

+ (NSString *)useDESDecryptString:(NSString *)str SetKey:(NSString *) sKey{
    return [[MyDES shareManager] decryptUseDES:str  key: sKey];
}

@end
